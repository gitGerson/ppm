# Spreadsheet-driven DetailSantri sync

## Implementation status

Implemented locally. Google Apps Script initiates both directions:
- Human sheet edits -> authenticated Laravel API -> database.
- A five-minute Apps Script timer -> paginated Laravel API -> sheet.

The API is disabled by default. No production API deployment or Google trigger installation has been performed. The production origin supplied by the project owner is `https://ppmalkautsarpwt.id`; the spreadsheet ID is still required.

Understand the problem -> map the impact -> then code.

## Files and behavior

- `routes/api.php`: versioned GET and PATCH routes.
- `AuthenticateSheetSync`: bearer authentication, disabled/unconfigured fail-closed behavior and private no-store responses.
- `ListSantriForSheetRequest` and `UpdateSantriFromSheetRequest`: pagination validation and strict editable-field allowlist.
- `DetailSantri::sheetUpdateRules()`: reusable field constraints matching the database, including unsigned integer ranges, identifier lengths and strict dates.
- `SantriSheetResource` / `SantriSheetSchema`: explicit fields, string identifiers and SHA-256 content revisions. Private upload paths and internal metadata are excluded.
- `SantriSheetApiSync`: Service Layer with a database transaction and row lock. No Google calls.
- `resources/js/santri-sheet-sync.js`: standalone Apps Script source, copied into Google's editor (not bundled into the website).

The integration credential can read and update all DetailSantri records, but only the approved columns. It cannot create/delete records or change IDs/ownership. Limit access to the private script project and the spreadsheet accordingly; API token possession is integration-level authorization, not the identity of the individual sheet editor. Existing Policies still apply to interactive application users.

No Repository abstraction or new PHP dependency was added. Artisan generated the framework classes. The route file was created manually because there is no route-file generator and `install:api` would install an unnecessary dependency.

## API contract

| Request | Result |
| --- | --- |
| `GET /api/v1/sheet-sync/santris?per_page=100` | `data`, ordered `schema`, and `next_cursor` (null on the final page). |
| `GET /api/v1/sheet-sync/santris?per_page=100&cursor=...` | Next ID-ordered page, maximum 100 rows. |
| `PATCH /api/v1/sheet-sync/santris/{id}` | Accepts `base_revision` and `changes`; returns canonical `data` and message. |

Each record contains `id`, `revision`, and `values`. JSON input uses strings for identifiers/dates, integers for numeric fields, booleans, and null for cleared optional fields. The script normalizes Indonesian boolean words and formatted integer amounts before sending them.

`409` preserves conflicting edits for a human choice. `422` rejects the whole update, leaving the database unchanged. `404` indicates a disabled API or missing record. `401` indicates bad/missing credentials. Successful duplicate retries acknowledge the current values without firing another model update. Requests are limited to 120/minute per integration by default.

## Deploy Laravel

Before deployment, back up the spreadsheet and reconcile pending edits. Stop the OLD scheduled sync commands and OLD observer producers, then drain the two old sync job classes while the previous release still exists. This release removes those classes: do not deploy it over a queue containing serialized old sync jobs. Keep unrelated queue workloads running.

1. Deploy this source and run `composer install --no-dev --optimize-autoloader` through the normal deployment process.
2. Configure these environment variables on the server:

   ```dotenv
   SANTRI_SHEET_API_ENABLED=true
   SANTRI_SHEET_API_TOKEN=<a newly generated secret of at least 32 characters>
   SANTRI_SHEET_REQUESTS_PER_MINUTE=120
   ```

3. Generate a credential locally on the server, for example `php -r 'echo bin2hex(random_bytes(32)), PHP_EOL;'`. Copy it privately to Apps Script properties; never put it in a cell, source commit, screenshot or chat.
4. Refresh the application's configuration cache using the existing deployment procedure. Confirm the authenticated GET returns JSON and an unauthenticated request fails.
5. No new database migration is required.

The public API base URL in Apps Script must use HTTPS, must not redirect, and must point at the Laravel origin (no trailing API path). Set Laravel's production URL/proxy configuration through the normal environment setup.

## Install Apps Script

Use a staging copy first. Google Apps Script access is not connected in this workspace, so the following setup must be performed by the spreadsheet owner.

1. Find the spreadsheet ID in its URL: `https://docs.google.com/spreadsheets/d/SPREADSHEET_ID/edit`.
2. Create a **standalone** Apps Script project owned by a stable administrator account. Only trusted maintainers should be project editors. Do not put credential-bearing code into a bound project accessible to ordinary sheet editors.
3. Copy all of `resources/js/santri-sheet-sync.js` into `Code.gs`.
4. In **Project Settings -> Script Properties**, set:

   | Property | Value |
   | --- | --- |
   | `API_BASE_URL` | `https://ppmalkautsarpwt.id` |
   | `API_TOKEN` | The same private secret configured in Laravel |
   | `SPREADSHEET_ID` | The ID from step 1 |
   | `SHEET_NAME` | `DetailSantri API` (recommended new tab) |

5. Run `syncSantriSheet` manually and authorize Google access. This creates the new data tab and protected `_SantriSync` metadata tab. It refuses mismatched headers instead of replacing the old sheet. A populated matching tab with unacknowledged edits requires explicit conflict resolution.
6. Check identifiers, representative fields, row counts and access permissions. Run the acceptance checks below.
7. Run `installSantriSync`. It installs one edit trigger and one five-minute timer, replacing only this script's previous triggers. Re-running it does not create duplicate triggers.
8. Run `syncSantriSheet` from the private script editor for manual refresh. Run `uninstallSantriSync` to stop automatic runs.

The sheet has `Status sinkronisasi` and `Resolusi` columns. Choose **Database** to accept database values, or **Sheet** to explicitly resubmit local values against the latest database revision. A new concurrent database change still produces another conflict. For a record deleted from the database, **Database** discards the orphaned sheet row; **Sheet** never recreates a database record.

The script protects headers, IDs, status and metadata. During sync it temporarily locks all data cells to prevent structural changes. The spreadsheet owner can bypass Google protections: do not sort, insert/delete rows or restructure columns during sync. Cell edits made in flight are re-read and merged so newer local edits survive.

Dirty rows are detected by comparing cells with their acknowledged baseline, even if an edit trigger was missed. Network failures, rate limiting and server errors retry with bounded backoff; unsent edits remain in the cells for the next run. Invalid/conflicting rows remain blocked until edited or explicitly resolved. The status header note shows the last completed refresh or a failure after the sheet has been opened. Earlier connection failures are visible in Apps Script Executions.

Full pagination must finish before records are merged or deleted. Database deletions clear only clean rows (or rows explicitly resolved to Database); pending orphan edits remain visible. Deleting a sheet row never deletes a database record. API calls never follow redirects with the bearer credential. Sheet writes use literal text to preserve leading zeros and prevent formula interpretation.

## Cleanup

Removed the deferred observer draft and retired the legacy observer registration, outbound sync service, two jobs, Artisan sync command, Laravel scheduler entries, Google config and FakeSheets test helper. Existing sync tests were rewritten around the replacement API; normalization/retry coverage also lives in the Node tests.

Removed `revolution/laravel-google-sheets` and its unused transitive dependencies from `composer.json` / `composer.lock`. The local vendor tree is tracked by this repository and contains over 36,000 files belonging to those packages; it was not mass-deleted. Run Composer install in the deployment environment to reconcile installed packages and package discovery.

Historical migrations and the old `sheet_hash` / `sheet_synced_at` columns remain to preserve existing database data and rollback compatibility. They are no longer read or written by synchronization. Service-account credentials already on disk were not deleted. No previous commit was reset.

## Verification

Local checks:
- 56 Pest tests, 157 assertions passed across sync, registration validation and WhatsApp registration.
- 16 Node tests passed for parsing, pagination failures, retries, conflicts, concurrent edits, deletion recovery and trigger setup.
- Targeted Larastan analysis passed.
- Pint applied to changed PHP files; Composer manifest/lock validation passed.

Commands:
```sh
php artisan test --compact tests/Feature/DetailSantriSheetSyncTest.php tests/Feature/PendaftaranValidationTest.php tests/Feature/PendaftaranWhatsappNotificationTest.php
node --test tests/Unit/SantriSheetScriptTest.cjs
php vendor/bin/pint --dirty --format agent
```

Pest was run with `DB_CONNECTION=sqlite` and `DB_DATABASE=:memory:`; do not run RefreshDatabase against live data.

Outstanding rollout acceptance:
- Deploy and test the actual HTTPS API with the configured credential.
- Verify Google trigger permissions, multi-row paste, timer recovery after failure, and conflict resolution.
- Verify edits during refresh, database deletion with local pending edits, and full-page failure behavior.
- Measure row volume, Google quota consumption and elapsed time (script yields after a four-minute work budget; a single Google request can outlast that budget).
- Verify concurrent write behavior against an isolated MySQL database; SQLite tests do not establish MySQL locking behavior.

If data volume exceeds the full-read execution budget, keep triggers disabled and implement incremental reads with deletion tracking before rollout. This release does not claim live Google or production validation.

## Rollback

Disable Apps Script triggers and Laravel API writes first. Preserve/reconcile pending sheet edits, then restore the previous release and its legacy scheduler/producers. Never enable both synchronization mechanisms together.

## References

- [Google: installable triggers](https://developers.google.com/apps-script/guides/triggers/installable)
- [Google: external API requests](https://developers.google.com/apps-script/guides/services/external)
- [Google: protected sheets and ranges](https://developers.google.com/apps-script/reference/spreadsheet/protection)
- [Google: Script Properties](https://developers.google.com/apps-script/guides/properties)
- [Laravel 12: pessimistic locking](https://laravel.com/docs/12.x/queries#pessimistic-locking)

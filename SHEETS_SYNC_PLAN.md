# Spreadsheet-driven DetailSantri sync

Status: implementation plan only. Application behavior has not changed.

## Decision and current draft

Google Apps Script will initiate both directions through an authenticated Laravel API:

- Sheet edits -> API -> database.
- Apps Script timer -> API -> current database records -> sheet.

Delayed visibility of database changes is accepted. Proposed initial polling interval: five minutes. Creation and deletion remain application-only; spreadsheet editors can update existing records.

The uncommitted draft is related to this feature, but is not the implementation to ship:

| Draft change | Decision |
| --- | --- |
| Replace queued jobs with deferred observer callbacks | Drop: Laravel still initiates outbound Google requests, contrary to the chosen design. Failures and lock timeouts have no durable retry. |
| Delete the two sync jobs | Relevant at cutover, after their callers are retired and pending jobs have drained. Do not ship separately. |
| Remove scheduled pull and full rewrite | Relevant at cutover, after Apps Script owns synchronization. Removing these now stops automatic imports and recovery. |
| Rewrite tests around deferred callbacks | Drop with the deferred implementation. Preserve existing sync coverage until replacement coverage passes. |
| Modify vendor Pest test-results cache | Drop generated runtime noise; exclude from the feature commit. |

Recommended cleanup: restore the six reviewed draft files to HEAD, then implement the new design in focused commits. The cleanup has not been performed: automatic approval review rejected the destructive restore because the conditional discard authorization was considered ambiguous. No existing commit is to be reset or rewritten. The latest commit, `9e6c8cc3`, concerns WhatsApp notifications and stays intact.

## Existing code to reuse

- `app/Support/SantriSheetSchema.php`: column names, editable allowlist, normalization and identifier formatting. Audit validation against database constraints before exposing an API; existing tolerant imports must not silently accept invalid API writes.
- `app/Models/DetailSantri.php`: Eloquent model and relationships. Put shared rules and messages in named static model methods; compose endpoint-specific checks in Form Requests.
- `app/Support/DetailSantriSheetSync.php`: retain legacy Google transport during transition. Reuse `withoutPush()` for API writes while the observer remains active.
- Existing sync tests: retain schema, normalization, conflict and identifier coverage; replace transport-specific assertions at cutover.

Use the Service Layer pattern with thin controllers and Eloquent. A Repository abstraction is unnecessary. Do not install dependencies or create new top-level directories for this work without approval.

## 1. Laravel API

Proposed versioned endpoints:

| Endpoint | Contract |
| --- | --- |
| `GET /api/v1/sheet-sync/santris` | Bounded, ID-ordered cursor pagination. Return explicit schema metadata, allowed field values and an opaque content revision for each row. |
| `PATCH /api/v1/sheet-sync/santris/{santri}` | Accept `base_revision` and an allowlisted map of changed fields. Return the canonical saved row and its new revision. |

Use an API Resource to serialize only approved columns. Preserve identifiers such as NIK, NISN and phone numbers as strings. Exclude credentials, private file contents and unrelated model attributes. Reject writes to IDs, ownership and sync metadata. API writes cannot create or delete records.

For the initial single integration, use a high-entropy bearer credential stored in Laravel environment-backed configuration and the private Apps Script project's Script Properties. Authenticate using dedicated middleware, constant-time comparison and fail-closed behavior when disabled or unconfigured. Restrict this credential to these endpoints and the approved record scope; do not treat a spreadsheet ID supplied by a caller as authentication. Add rate limiting and audit record IDs/outcomes without logging tokens or sensitive row bodies. Existing user Policies remain responsible for interactive app access.

Sanctum is not currently installed. Do not run `install:api` or add a dependency implicitly. Register the API routes in `bootstrap/app.php`; inspect available Artisan generators first. A route file may be created manually only if this installation has no suitable generator, with that reason recorded in the implementation notes.

Require reachable HTTPS for Apps Script. Localhost on the developer machine cannot be called directly by Google.

## 2. Conflict and retry contract

Proposed default: reject conflicting writes for explicit resolution, rather than silently discarding either side. This is an implementation assumption; the existing `conflict_winner` setting belongs to the legacy transport until cutover.

Compute the revision from a stable canonical representation of synchronized model fields, not solely from second-resolution `updated_at`. Within a database transaction, load the row with `lockForUpdate()`, compare the supplied base revision, validate and save. Return `409` with the canonical row when another edit changed the revision. If the requested values already match, return success without repeating mutation side effects; this handles a retry after a lost response.

Return `422` for invalid fields, `404` for missing records and `401`/`403` for authentication/authorization failures. The script retries transient network errors, `429` and `5xx` with bounded backoff, retaining pending edits for later runs. Validation errors and conflicts remain visible and are not retried blindly.

## 3. Apps Script

Keep credential-bearing code in a standalone project restricted to trusted maintainers. Ordinary spreadsheet editing permission must not grant access to the integration credential. Configure spreadsheet ID, tab and API base URL as script configuration; credentials never appear in cells or committed source.

Install one spreadsheet edit trigger and one five-minute time trigger. The edit trigger processes affected rows, including multi-cell pastes, and ignores headers and read-only columns. The timer retries pending edits and fetches database changes. Both call the same synchronization function under a Script Lock. Trigger installation must be repeatable without creating duplicates.

Maintain last acknowledged values, revision and pending/error state per record in a protected metadata tab, with no secrets. Detect dirty rows by comparing editable cells against their acknowledged values so a missed trigger does not lose an edit. Send only changed fields.

Fetch all pages successfully before using the ID set to identify deleted records. Merge by record ID, never by row position. New database records appear in the sheet; missing database IDs are removed only after confirming absence and preserving any pending edits for review. Deleting a sheet row must never delete a database record; the next refresh restores it.

Do not overwrite dirty, invalid or conflicted rows during refresh. Re-read the cells before applying a network response: Script Lock serializes scripts, but does not stop a human editing cells while a request is in flight. Preserve newer edits, and include a browser/manual acceptance test for this race. Avoid whole-sheet clear/rewrite. Protect structural changes during sync and re-resolve IDs before writes. Use text-safe cell writes so leading zeros and long identifiers survive and user text is not interpreted as formulas.

Show last successful refresh and per-row pending/error/conflict status. A conflict resolution action must explicitly choose the database value or resubmit the sheet edit against the current revision. A manual refresh may be added through a trusted execution path; do not expose the bearer token in a sheet-bound menu script shared with editors.

Start with a full paginated read on each timer run, avoiding a new change-log schema. Measure real row count, payload size and execution duration before rollout. If it exceeds the execution budget, design incremental reads with deletion tracking before enabling production triggers.

## 4. Implementation sequence and generators

Understand the problem -> map the impact -> then code. Inspect sibling conventions and version-specific Boost docs before each code phase.

1. Add disabled-by-default API configuration, authentication, Resources, Requests and Service logic.
2. Add API regression coverage and run focused tests using an isolated test database.
3. Implement and exercise Apps Script against a staging sheet and HTTPS API.
4. Complete the cutover checks below, then retire the legacy observer, jobs and scheduler entries together.

Candidate Artisan commands; verify command availability and options before executing:

```sh
php artisan make:controller Api/V1/SantriSheetSyncController --api --no-interaction
php artisan make:middleware AuthenticateSheetSync --no-interaction
php artisan make:request UpdateSantriFromSheetRequest --no-interaction
php artisan make:resource SantriSheetResource --no-interaction
php artisan make:class Support/SantriSheetApiSync --no-interaction
php artisan make:test --pest SantriSheetApiSyncTest --no-interaction
```

Keep only the required controller actions. No model or migration is planned initially. If schema changes become necessary, inspect the schema with Boost and generate a reversible migration with Artisan.

## 5. Verification and acceptance

- API authentication, disabled mode, rate limiting and field/record authorization.
- Valid updates, invalid values, read-only field rejection, identifier round trips and pagination.
- Revision conflicts, concurrent updates, duplicate retries and rollback behavior.
- Incoming API updates make no outbound Google request.
- Human edits reach the database, including multi-row paste and a missed-trigger recovery run.
- App edits appear on the next successful timer run; failures leave pending edits intact.
- Refresh preserves edits made while an API request is in flight.
- Failed pagination never causes row deletion; database deletion never recreates a record from a stale sheet edit.
- Trigger overlap and repeated setup do not duplicate writes or triggers.
- After cutover, app saves/deletes and scheduled tasks make no Google Sheets calls.

Run focused Pest tests, then the relevant existing registration/DetailSantri tests. Run `vendor/bin/pint --dirty --format agent` for PHP changes and targeted static analysis. Validate transaction concurrency against MySQL as well as isolated SQLite tests. Perform Apps Script checks on a staging copy with representative data; unit tests cannot establish trigger delivery or Google quotas.

## 6. Cutover and rollback

Before enabling the new script, back up the sheet and reconcile outstanding legacy edits. Deploy the API with access restricted, pause legacy sync scheduling and producers, and drain existing sync jobs before deleting their classes. Retain the other application queue workloads. Establish an acknowledged baseline before enabling Apps Script edits and its timer. Never run legacy full rewrites alongside spreadsheet-driven synchronization.

Retire `DetailSantriObserver` and its `ObservedBy` registration, the two sync jobs and the two scheduler entries after the replacement passes acceptance. Keep the manual legacy command disabled during the new mode; decide its final removal in the cutover commit. Leave old sync columns and the Google package in place initially; dependency removal is separate scope.

Rollback: disable Apps Script triggers and API writes first, preserve pending edits, reconcile them, then restore the legacy producers and schedules. Do not allow both systems to write at once.

Completion means both directions work from Apps Script, errors and conflicts are recoverable, and Laravel no longer initiates Google sync. This planning commit does not deploy an API or install any triggers.

## References

- [Google: installable triggers](https://developers.google.com/apps-script/guides/triggers/installable)
- [Google: external API requests](https://developers.google.com/apps-script/guides/services/external)
- [Google: Lock Service](https://developers.google.com/apps-script/reference/lock)
- [Google: Properties Service](https://developers.google.com/apps-script/guides/properties)
- [Laravel 12: pessimistic locking](https://laravel.com/docs/12.x/queries#pessimistic-locking)
- [Laravel 12: API Resources](https://laravel.com/docs/12.x/eloquent-resources)

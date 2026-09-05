/* Copy this file into a private standalone Google Apps Script project. */
const SYNC_META = '_SantriSync';
const SYNC_PROTECTION = 'Santri API sync';
const SYNC_HANDLERS = ['syncSantriSheet', 'onSantriEdit'];

function syncConfig() {
    const p = PropertiesService.getScriptProperties().getProperties();
    const base = (p.API_BASE_URL || '').replace(/\/$/, '');
    if (!/^https:\/\/[^/?#]+$/.test(base) || !p.SPREADSHEET_ID || !p.API_TOKEN || p.API_TOKEN.length < 32) {
        throw new Error('Set API_BASE_URL (HTTPS origin), SPREADSHEET_ID and API_TOKEN in Script Properties.');
    }
    return { base, token: p.API_TOKEN, spreadsheet: p.SPREADSHEET_ID, tab: p.SHEET_NAME || 'DetailSantri API' };
}

function installSantriSync() {
    syncSantriSheet(); // Validate configuration and initialize before installing triggers.
    const config = syncConfig();
    ScriptApp.getProjectTriggers().forEach(trigger => {
        if (SYNC_HANDLERS.includes(trigger.getHandlerFunction())) ScriptApp.deleteTrigger(trigger);
    });
    ScriptApp.newTrigger('onSantriEdit').forSpreadsheet(config.spreadsheet).onEdit().create();
    ScriptApp.newTrigger('syncSantriSheet').timeBased().everyMinutes(5).create();
}

function uninstallSantriSync() {
    ScriptApp.getProjectTriggers().forEach(trigger => {
        if (SYNC_HANDLERS.includes(trigger.getHandlerFunction())) ScriptApp.deleteTrigger(trigger);
    });
}

function onSantriEdit(event) {
    if (!event || !event.range || event.range.getLastRow() < 2) return;
    const config = syncConfig();
    if (event.source.getId() !== config.spreadsheet || event.range.getSheet().getName() !== config.tab) return;
    syncSantriSheet();
}

function apiRequest(config, path, method, payload, deadline) {
    for (let attempt = 0; attempt < 3; attempt++) {
        checkDeadline(deadline);
        let response;
        try {
            response = UrlFetchApp.fetch(config.base + '/api/v1/sheet-sync/santris' + path, {
                method: method || 'get',
                headers: { Authorization: 'Bearer ' + config.token, Accept: 'application/json' },
                contentType: 'application/json',
                ...(payload ? { payload: JSON.stringify(payload) } : {}),
                muteHttpExceptions: true,
                followRedirects: false,
                validateHttpsCertificates: true,
            });
        } catch (_) {
            if (attempt === 2) throw new Error('Network failure; pending edits retained.');
        }
        if (response) {
            const code = response.getResponseCode();
            if (code !== 429 && code < 500) {
                if (![200, 404, 409, 422].includes(code)) throw new Error('API HTTP ' + code + '; sync stopped.');
                let body;
                try { body = JSON.parse(response.getContentText()); }
                catch (_) { throw new Error('Invalid API response; sync stopped.'); }
                return { code, body };
            }
            if (attempt === 2) throw new Error('API HTTP ' + code + '; pending edits retained.');
        }
        Utilities.sleep(1000 * Math.pow(2, attempt));
    }
}

function checkDeadline(deadline) {
    if (Date.now() > deadline) throw new Error('Sync time budget reached; remaining edits will retry next run.');
}

function fetchSantris(config, deadline) {
    const records = new Map();
    const cursors = new Set();
    let cursor = null;
    let schema;
    do {
        const result = apiRequest(config, '?per_page=100' + (cursor ? '&cursor=' + encodeURIComponent(cursor) : ''), 'get', null, deadline);
        if (result.code !== 200 || !Array.isArray(result.body.data) || !result.body.schema || !Object.prototype.hasOwnProperty.call(result.body, 'next_cursor')) {
            throw new Error('Incomplete API page; sheet unchanged.');
        }
        if (schema && JSON.stringify(schema) !== JSON.stringify(result.body.schema)) throw new Error('Schema changed during pagination.');
        schema = result.body.schema;
        result.body.data.forEach(record => {
            validateRecord(record, schema);
            const id = String(record.id);
            if (records.has(id)) throw new Error('Duplicate API record.');
            records.set(id, record);
        });
        cursor = result.body.next_cursor;
        if (cursor !== null && (typeof cursor !== 'string' || !cursor || cursors.has(cursor))) throw new Error('Invalid pagination cursor.');
        cursors.add(cursor);
    } while (cursor !== null);
    return { records, schema };
}

function validateRecord(record, schema) {
    if (!record || !Number.isSafeInteger(record.id) || record.id < 1 || !/^[a-f0-9]{64}$/.test(record.revision) || !record.values) {
        throw new Error('Invalid API record.');
    }
    if (String(record.values.id) !== String(record.id) || Object.keys(schema).some(key => !Object.prototype.hasOwnProperty.call(record.values, key))) {
        throw new Error('Incomplete API record.');
    }
}

function displayValue(value) {
    return value === null || value === undefined ? '' : typeof value === 'boolean' ? (value ? 'Ya' : 'Tidak') : String(value);
}

function safeText(value) {
    const text = displayValue(value);
    // The apostrophe is Sheets' literal-text prefix, never a formula.
    return text === '' ? '' : "'" + text;
}

function parseCell(raw, definition) {
    const value = String(raw).trim();
    if (value === '') return null;
    if (definition.type === 'bool') {
        if (/^(ya|yes|true|1|y|sudah)$/i.test(value)) return true;
        if (/^(tidak|no|false|0|n|belum)$/i.test(value)) return false;
        throw new Error('Gunakan Ya atau Tidak.');
    }
    if (definition.type === 'int' || definition.type === 'year') {
        const number = value.replace(/^Rp\s*/i, '');
        if (!/^\d+$/.test(number) && !(definition.type === 'int' && /^\d{1,3}(\.\d{3})+$/.test(number))) {
            throw new Error('Bilangan bulat tidak valid.');
        }
        const parsed = Number(number.replace(/\./g, ''));
        if (!Number.isSafeInteger(parsed)) throw new Error('Bilangan terlalu besar.');
        return parsed;
    }
    if (definition.type === 'date' && !/^\d{4}-\d{2}-\d{2}$/.test(value)) throw new Error('Gunakan YYYY-MM-DD.');
    if (definition.type === 'enum') {
        const match = definition.values.find(candidate => candidate.toLowerCase() === value.toLowerCase());
        if (!match) throw new Error('Pilihan tidak valid.');
        return match;
    }
    return value;
}

function changedFields(cells, baseline, schema) {
    const changes = {};
    Object.keys(schema).forEach((key, index) => {
        if (schema[key].editable && cells[index] !== displayValue(baseline[key])) {
            const parsed = parseCell(cells[index], schema[key]);
            if (parsed !== baseline[key]) changes[key] = parsed;
        }
    });
    return changes;
}

function dirtyCells(cells, baseline, schema) {
    return Object.keys(schema).some((key, index) => schema[key].editable && cells[index] !== displayValue(baseline[key]));
}

function hardProtect(sheet) {
    let protection = sheet.getProtections(SpreadsheetApp.ProtectionType.SHEET).find(p => p.getDescription() === SYNC_PROTECTION);
    if (!protection) protection = sheet.protect().setDescription(SYNC_PROTECTION);
    protection.setWarningOnly(false);
    protection.addEditor(Session.getEffectiveUser());
    protection.removeEditors(protection.getEditors().filter(user => user.getEmail() !== Session.getEffectiveUser().getEmail()));
    if (protection.canDomainEdit()) protection.setDomainEdit(false);
    protection.setUnprotectedRanges([]);
    return protection;
}

function ensureSize(sheet, rows, columns) {
    if (sheet.getMaxRows() < rows) sheet.insertRowsAfter(sheet.getMaxRows(), rows - sheet.getMaxRows());
    if (sheet.getMaxColumns() < columns) sheet.insertColumnsAfter(sheet.getMaxColumns(), columns - sheet.getMaxColumns());
}

function writeText(range, rows) {
    range.setNumberFormat('@').setValues(rows.map(row => row.map(safeText)));
}

function findRow(sheet, id) {
    if (sheet.getLastRow() < 2) return null;
    const matches = sheet.getRange(2, 1, sheet.getLastRow() - 1, 1).getDisplayValues()
        .map((row, index) => row[0] === String(id) ? index + 2 : null).filter(Boolean);
    if (matches.length > 1) throw new Error('Duplicate sheet ID ' + id + '; resolve duplicate rows first.');
    return matches[0] || null;
}

function readCells(sheet, id, width) {
    const row = findRow(sheet, id);
    return row ? { row, cells: sheet.getRange(row, 1, 1, width).getDisplayValues()[0] } : null;
}

function sameCells(left, right) {
    return JSON.stringify(left) === JSON.stringify(right);
}

function setStatus(sheet, id, width, message) {
    const row = findRow(sheet, id);
    if (row) writeText(sheet.getRange(row, width + 1), [[message]]);
}

function saveState(meta, id, state) {
    const row = findRow(meta, id) || meta.getLastRow() + 1;
    ensureSize(meta, row, 2);
    writeText(meta.getRange(row, 1, 1, 2), [[id, JSON.stringify(state)]]);
    SpreadsheetApp.flush();
}

function loadState(meta) {
    const states = new Map();
    if (meta.getLastRow() < 2) return states;
    meta.getRange(2, 1, meta.getLastRow() - 1, 2).getDisplayValues().forEach(row => {
        if (!row[0]) return;
        if (states.has(row[0])) throw new Error('Duplicate metadata ID.');
        try { states.set(row[0], JSON.parse(row[1])); }
        catch (_) { throw new Error('Invalid sync metadata; restore metadata backup.'); }
    });
    return states;
}

function applyAcknowledged(sheet, meta, record, schema, expectedCells) {
    const keys = Object.keys(schema);
    const latest = readCells(sheet, record.id, keys.length);
    if (!latest) return;
    if (sameCells(latest.cells, expectedCells)) {
        writeText(sheet.getRange(latest.row, 1, 1, keys.length), [keys.map(key => record.values[key])]);
        writeText(sheet.getRange(latest.row, keys.length + 2), [['']]);
        setStatus(sheet, record.id, keys.length, 'Tersinkron');
    } else {
        // Merge fields the human has not edited since the request began.
        keys.forEach((key, index) => {
            if (latest.cells[index] === expectedCells[index]) {
                writeText(sheet.getRange(latest.row, index + 1), [[record.values[key]]]);
            }
        });
        setStatus(sheet, record.id, keys.length, 'Menunggu: edit baru saat sinkronisasi');
    }
    saveState(meta, record.id, { ack: record, blocked: null });
}

function syncRow(sheet, meta, record, state, schema, config, deadline) {
    const keys = Object.keys(schema);
    const current = readCells(sheet, record.id, keys.length);
    if (!current) {
        const row = sheet.getLastRow() + 1;
        ensureSize(sheet, row, keys.length + 2);
        writeText(sheet.getRange(row, 1, 1, keys.length), [keys.map(key => record.values[key])]);
        saveState(meta, record.id, { ack: record, blocked: null });
        setStatus(sheet, record.id, keys.length, 'Tersinkron');
        return;
    }
    const resolution = sheet.getRange(current.row, keys.length + 2).getDisplayValue();
    if (resolution === 'Database') {
        applyAcknowledged(sheet, meta, record, schema, current.cells);
        return;
    }
    if (!state || !state.ack) {
        if (!dirtyCells(current.cells, record.values, schema)) {
            applyAcknowledged(sheet, meta, record, schema, current.cells);
        } else {
            saveState(meta, record.id, { ack: record, blocked: JSON.stringify(current.cells) });
            setStatus(sheet, record.id, keys.length, 'Konflik awal: pilih Database atau Sheet');
        }
        return;
    }
    const fingerprint = JSON.stringify(current.cells);
    if (state.blocked === fingerprint && resolution !== 'Sheet') return;
    let changes;
    try { changes = changedFields(current.cells, state.ack.values, schema); }
    catch (error) {
        setStatus(sheet, record.id, keys.length, 'Tidak valid: ' + error.message);
        saveState(meta, record.id, { ...state, blocked: fingerprint });
        return;
    }
    if (resolution === 'Sheet') {
        // Explicit resolution uses the newest DB revision and the user's displayed editable values.
        try { changes = changedFields(current.cells, record.values, schema); }
        catch (error) { setStatus(sheet, record.id, keys.length, 'Tidak valid: ' + error.message); return; }
    }
    if (!Object.keys(changes).length) {
        applyAcknowledged(sheet, meta, record, schema, current.cells);
        return;
    }
    setStatus(sheet, record.id, keys.length, 'Mengirim...');
    if (resolution === 'Sheet') writeText(sheet.getRange(current.row, keys.length + 2), [['']]);
    const result = apiRequest(config, '/' + record.id, 'patch', {
        base_revision: resolution === 'Sheet' ? record.revision : state.ack.revision,
        changes,
    }, deadline);
    if (result.code === 200) {
        validateRecord(result.body.data, schema);
        applyAcknowledged(sheet, meta, result.body.data, schema, current.cells);
    } else {
        const message = result.code === 409 ? 'Konflik: pilih Database atau Sheet' : result.code === 404 ? 'Dihapus di database; edit lokal dipertahankan' : 'Tidak valid: ' + Object.keys(result.body.errors || {}).join(', ');
        setStatus(sheet, record.id, keys.length, message);
        // Consume a resolution choice so another concurrent DB edit requires a new explicit choice.
        writeText(sheet.getRange(findRow(sheet, record.id), keys.length + 2), [['']]);
        saveState(meta, record.id, { ...state, blocked: fingerprint });
    }
}

function syncSantriSheet() {
    const lock = LockService.getScriptLock();
    if (!lock.tryLock(1000)) return;
    let sheet, protection, schema;
    try {
        const deadline = Date.now() + 240000;
        const config = syncConfig();
        const fetched = fetchSantris(config, deadline); // No mutations until all pages succeed.
        schema = fetched.schema;
        const keys = Object.keys(schema);
        const book = SpreadsheetApp.openById(config.spreadsheet);
        sheet = book.getSheetByName(config.tab) || book.insertSheet(config.tab);
        protection = hardProtect(sheet); // Prevent structural edits while network requests are in flight.
        ensureSize(sheet, 2, keys.length + 2);
        const headers = keys.map(key => schema[key].label).concat(['Status sinkronisasi', 'Resolusi']);
        if (sheet.getLastRow() && !sameCells(sheet.getRange(1, 1, 1, headers.length).getDisplayValues()[0], headers)) {
            throw new Error('Headers differ. Use a new empty tab or restore the expected headers.');
        }
        writeText(sheet.getRange(1, 1, 1, headers.length), [headers]);
        sheet.setFrozenRows(1);
        const meta = book.getSheetByName(SYNC_META) || book.insertSheet(SYNC_META);
        hardProtect(meta);
        writeText(meta.getRange(1, 1, 1, 2), [['ID', 'Acknowledged state']]);
        const states = loadState(meta);
        // Validate duplicate IDs before any row writes.
        if (sheet.getLastRow() > 1) {
            const ids = sheet.getRange(2, 1, sheet.getLastRow() - 1, 1).getDisplayValues().map(row => row[0]).filter(Boolean);
            if (new Set(ids).size !== ids.length) throw new Error('Duplicate sheet IDs; sync stopped.');
        }
        for (const record of fetched.records.values()) {
            checkDeadline(deadline);
            syncRow(sheet, meta, record, states.get(String(record.id)), schema, config, deadline);
        }
        for (const [id, state] of states) {
            if (fetched.records.has(id)) continue;
            const current = readCells(sheet, id, keys.length);
            if (!current) continue;
            const resolution = sheet.getRange(current.row, keys.length + 2).getDisplayValue();
            if ((!state.ack || dirtyCells(current.cells, state.ack.values, schema)) && resolution !== 'Database') {
                setStatus(sheet, id, keys.length, 'Dihapus di database; edit lokal dipertahankan');
            } else {
                const latest = readCells(sheet, id, keys.length);
                if (latest && sameCells(latest.cells, current.cells)) {
                    sheet.getRange(latest.row, 1, 1, keys.length + 2).clearContent();
                    const metaRow = findRow(meta, id);
                    if (metaRow) meta.getRange(metaRow, 1, 1, 2).clearContent();
                }
            }
        }
        // Unrecognized rows are never sent as new database records.
        if (sheet.getLastRow() > 1) {
            sheet.getRange(2, 1, sheet.getLastRow() - 1, keys.length).getDisplayValues().forEach((cells, index) => {
                if (cells.some(Boolean) && (!cells[0] || (!fetched.records.has(cells[0]) && !states.has(cells[0])))) {
                    writeText(sheet.getRange(index + 2, keys.length + 1), [['ID tidak ditemukan; buat data melalui aplikasi']]);
                }
            });
        }
        sheet.getRange(1, keys.length + 1).setNote('Refresh terakhir: ' + new Date().toISOString());
    } catch (error) {
        if (sheet && schema) sheet.getRange(1, Object.keys(schema).length + 1).setNote(error.message);
        throw error;
    } finally {
        if (sheet && protection && schema) {
            const keys = Object.keys(schema);
            const rows = sheet.getMaxRows() - 1;
            const editable = keys.flatMap((key, index) => schema[key].editable ? [sheet.getRange(2, index + 1, rows, 1)] : []);
            editable.push(sheet.getRange(2, keys.length + 2, rows, 1));
            protection.setUnprotectedRanges(editable);
            sheet.getRange(2, keys.length + 2, rows, 1).setDataValidation(
                SpreadsheetApp.newDataValidation().requireValueInList(['Database', 'Sheet'], true).setAllowInvalid(false).build()
            );
            SpreadsheetApp.flush();
        }
        lock.releaseLock();
    }
}

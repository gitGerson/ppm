const { test } = require('node:test');
const assert = require('node:assert/strict');
const fs = require('node:fs');
const vm = require('node:vm');
const path = require('node:path');
const source = fs.readFileSync(path.join(__dirname, '../../resources/js/santri-sheet-sync.js'), 'utf8');

function script(overrides = {}) {
    const context = vm.createContext({ console, ...overrides });
    vm.runInContext(source, context);
    return context;
}
const schema = {
    id: { label: 'ID', type: 'int', editable: false },
    name: { label: 'Name', type: 'string', editable: true },
    phone: { label: 'Phone', type: 'identifier', editable: true },
};
const record = (name = 'Database', phone = '0012') => ({ id: 1, revision: 'a'.repeat(64), values: { id: 1, name, phone } });

test('parses human input without corrupting identifiers, negative numbers or fractions', () => {
    const s = script();
    assert.equal(s.parseCell('0012345678901234', { type: 'identifier' }), '0012345678901234');
    assert.equal(s.parseCell('Rp 5.000.000', { type: 'int' }), 5000000);
    assert.equal(s.parseCell('Tidak', { type: 'bool' }), false);
    assert.equal(s.parseCell('', { type: 'string' }), null);
    assert.throws(() => s.parseCell('-2', { type: 'int' }));
    assert.throws(() => s.parseCell('2.5', { type: 'int' }));
    assert.throws(() => s.parseCell('2000/02/30', { type: 'date' }));
    assert.equal(s.safeText('=IMPORTXML("secret")'), '\'=IMPORTXML("secret")');
    assert.equal(s.safeText('0012'), "'0012");
});

test('sends only changed editable fields and handles equivalent formatted values', () => {
    const s = script();
    assert.deepEqual(JSON.parse(JSON.stringify(s.changedFields(['99', 'Sheet', '0012'], record().values, schema))), { name: 'Sheet' });
    assert.equal(s.dirtyCells(['99', 'Database', '0012'], record().values, schema), false);
});

test('pagination failures never return a partial collection', () => {
    const s = script();
    let calls = 0;
    s.apiRequest = () => ++calls === 1
        ? { code: 200, body: { data: [record()], schema, next_cursor: 'next' } }
        : { code: 404, body: {} };
    assert.throws(() => s.fetchSantris({}, Infinity), /Incomplete API page/);
    assert.equal(calls, 2);
});

test('rejects repeated pagination cursors and duplicate records', () => {
    const s = script();
    s.apiRequest = () => ({ code: 200, body: { data: [record()], schema, next_cursor: 'next' } });
    assert.throws(() => s.fetchSantris({}, Infinity), /Duplicate API record/);
});

test('retries transient failures and never follows redirects with the credential', () => {
    let calls = 0;
    const options = [];
    const s = script({
        UrlFetchApp: { fetch: (_, opts) => {
            options.push(opts);
            return { getResponseCode: () => ++calls < 3 ? 503 : 200, getContentText: () => '{}' };
        } },
        Utilities: { sleep: () => {} },
    });
    assert.equal(s.apiRequest({ base: 'https://example.test', token: 'secret' }, '', 'get', null, Infinity).code, 200);
    assert.equal(calls, 3);
    assert.equal(options.every(option => option.followRedirects === false), true);
});

test('expired time budget leaves requests unissued', () => {
    const s = script();
    assert.throws(() => s.apiRequest({}, '', 'get', null, 0), /time budget/);
});

function rowHarness(current, resolution = '') {
    const s = script();
    const writes = [];
    const states = [];
    const statuses = [];
    const sheet = { getRange: (...args) => ({ args, getDisplayValue: () => resolution }) };
    s.readCells = () => ({ row: 2, cells: [...current] });
    s.findRow = () => 2;
    s.writeText = (range, values) => writes.push({ range: range.args, values: JSON.parse(JSON.stringify(values)) });
    s.saveState = (_, id, state) => states.push({ id, state: JSON.parse(JSON.stringify(state)) });
    s.setStatus = (_, id, width, message) => statuses.push(message);
    return { s, sheet, writes, states, statuses };
}

test('refresh merges untouched fields but preserves edits made in flight', () => {
    const h = rowHarness(['1', 'New local edit', '0012']);
    h.s.applyAcknowledged(h.sheet, {}, record('Remote name', '0099'), schema, ['1', 'Old name', '0012']);
    assert.equal(h.writes.some(write => write.range[1] === 2), false);
    assert.equal(h.writes.some(write => write.range[1] === 3 && write.values[0][0] === '0099'), true);
    assert.equal(h.states[0].state.ack.values.name, 'Remote name');
});

test('a changed row posts with the acknowledged revision', () => {
    const h = rowHarness(['1', 'Sheet', '0012']);
    let sent;
    h.s.apiRequest = (_, path, method, payload) => {
        sent = { path, method, payload };
        return { code: 200, body: { data: record('Sheet') } };
    };
    h.s.syncRow(h.sheet, {}, record(), { ack: record(), blocked: null }, schema, {}, Infinity);
    assert.equal(sent.path, '/1');
    assert.equal(sent.payload.base_revision, 'a'.repeat(64));
    assert.deepEqual(JSON.parse(JSON.stringify(sent.payload.changes)), { name: 'Sheet' });
    assert.equal(h.states[0].state.ack.values.name, 'Sheet');
});

test('conflicts preserve the baseline and block automatic resubmission', () => {
    const cells = ['1', 'Sheet', '0012'];
    const h = rowHarness(cells);
    let requests = 0;
    h.s.apiRequest = () => { requests++; return { code: 409, body: { data: record('New DB') } }; };
    h.s.syncRow(h.sheet, {}, record(), { ack: record('Old DB'), blocked: null }, schema, {}, Infinity);
    assert.equal(h.states[0].state.ack.values.name, 'Old DB');
    assert.equal(h.states[0].state.blocked, JSON.stringify(cells));
    h.s.syncRow(h.sheet, {}, record(), h.states[0].state, schema, {}, Infinity);
    assert.equal(requests, 1);
});

test('explicit Sheet resolution uses the current server revision once', () => {
    const h = rowHarness(['1', 'Sheet', '0012'], 'Sheet');
    const newer = { ...record('New DB'), revision: 'b'.repeat(64) };
    let sent;
    h.s.apiRequest = (_, path, method, payload) => {
        sent = payload;
        return { code: 409, body: { data: newer } };
    };
    h.s.syncRow(h.sheet, {}, newer, { ack: record('Old DB'), blocked: JSON.stringify(['1', 'Sheet', '0012']) }, schema, {}, Infinity);
    assert.equal(sent.base_revision, 'b'.repeat(64));
    assert.equal(h.writes.some(write => write.range[1] === 5 && write.values[0][0] === ''), true);
});

test('initial dirty rows require a choice instead of overwriting either side', () => {
    const h = rowHarness(['1', 'Unsent edit', '0012']);
    h.s.apiRequest = () => { throw new Error('Must not send'); };
    h.s.syncRow(h.sheet, {}, record(), undefined, schema, {}, Infinity);
    assert.equal(h.writes.length, 0);
    assert.match(h.statuses[0], /Konflik awal/);
});

test('trigger setup is repeatable and preserves unrelated triggers', () => {
    const removed = [], created = [];
    const s = script({ ScriptApp: {
        getProjectTriggers: () => ['syncSantriSheet', 'onSantriEdit', 'unrelated'].map(name => ({ getHandlerFunction: () => name })),
        deleteTrigger: trigger => removed.push(trigger.getHandlerFunction()),
        newTrigger: name => {
            const builder = { forSpreadsheet: () => builder, onEdit: () => builder, timeBased: () => builder, everyMinutes: () => builder, create: () => created.push(name) };
            return builder;
        },
    } });
    s.syncSantriSheet = () => {};
    s.syncConfig = () => ({ spreadsheet: 'test' });
    s.installSantriSync();
    assert.deepEqual(removed, ['syncSantriSheet', 'onSantriEdit']);
    assert.deepEqual(created, ['onSantriEdit', 'syncSantriSheet']);
});

function workbookHarness(cells, ack = record()) {
    class Sheet {
        constructor(name, rows = []) { this.name = name; this.cells = rows; this.rowCount = 20; this.colCount = 10; }
        getMaxRows() { return this.rowCount; }
        getMaxColumns() { return this.colCount; }
        insertRowsAfter(_, count) { this.rowCount += count; }
        insertColumnsAfter(_, count) { this.colCount += count; }
        getLastRow() {
            for (let i = this.cells.length - 1; i >= 0; i--) if ((this.cells[i] || []).some(Boolean)) return i + 1;
            return 0;
        }
        setFrozenRows() {}
        getRange(row, column, height = 1, width = 1) {
            const sheet = this;
            const range = {
                getDisplayValues: () => Array.from({ length: height }, (_, r) => Array.from({ length: width }, (_, c) => String(sheet.cells[row - 1 + r]?.[column - 1 + c] ?? ''))),
                getDisplayValue: () => range.getDisplayValues()[0][0],
                setNumberFormat: () => range,
                setValues: values => {
                    values.forEach((valuesRow, r) => valuesRow.forEach((value, c) => {
                        sheet.cells[row - 1 + r] ||= [];
                        sheet.cells[row - 1 + r][column - 1 + c] = String(value).replace(/^'/, '');
                    }));
                    return range;
                },
                clearContent: () => range.setValues(Array.from({ length: height }, () => Array(width).fill(''))),
                setNote: () => range,
                setDataValidation: () => range,
            };
            return range;
        }
    }
    const data = new Sheet('DetailSantri API', [['ID', 'Name', 'Phone', 'Status sinkronisasi', 'Resolusi'], ...(cells ? [cells] : [])]);
    const meta = new Sheet('_SantriSync', [['ID', 'Acknowledged state'], ...(ack ? [['1', JSON.stringify({ ack, blocked: null })]] : [])]);
    const sheets = new Map([[data.name, data], [meta.name, meta]]);
    let releases = 0;
    const validation = { requireValueInList: () => validation, setAllowInvalid: () => validation, build: () => ({}) };
    const s = script({
        PropertiesService: { getScriptProperties: () => ({ getProperties: () => ({ API_BASE_URL: 'https://example.test', API_TOKEN: 'a'.repeat(64), SPREADSHEET_ID: 'book' }) }) },
        LockService: { getScriptLock: () => ({ tryLock: () => true, releaseLock: () => releases++ }) },
        SpreadsheetApp: {
            openById: () => ({ getSheetByName: name => sheets.get(name), insertSheet: name => { const sheet = new Sheet(name); sheets.set(name, sheet); return sheet; } }),
            flush: () => {}, newDataValidation: () => validation,
        },
    });
    s.hardProtect = () => ({ setUnprotectedRanges: () => {} });
    return { s, data, meta, releases: () => releases };
}

test('full refresh updates clean rows and releases the script lock', () => {
    const h = workbookHarness(['1', 'Database', '0012']);
    h.s.apiRequest = () => ({ code: 200, body: { data: [record('New DB')], schema, next_cursor: null } });
    h.s.syncSantriSheet();
    assert.equal(h.data.cells[1][1], 'New DB');
    assert.equal(h.data.cells[1][3], 'Tersinkron');
    assert.equal(h.releases(), 1);
});

test('a manually deleted sheet row is restored without creating a database record', () => {
    const h = workbookHarness(null);
    h.s.apiRequest = (_, path, method) => {
        assert.equal(method, 'get');
        return { code: 200, body: { data: [record()], schema, next_cursor: null } };
    };
    h.s.syncSantriSheet();
    assert.equal(h.data.cells[1][0], '1');
});

test('deleted database rows preserve pending edits until explicitly discarded', () => {
    const h = workbookHarness(['1', 'Pending edit', '0012']);
    h.s.apiRequest = () => ({ code: 200, body: { data: [], schema, next_cursor: null } });
    h.s.syncSantriSheet();
    assert.equal(h.data.cells[1][1], 'Pending edit');
    assert.match(h.data.cells[1][3], /edit lokal dipertahankan/);
    h.data.cells[1][4] = 'Database';
    h.s.syncSantriSheet();
    assert.equal(h.data.cells[1][0], '');
    assert.equal(h.meta.cells[1][0], '');
});

test('a failed second page leaves sheet content and metadata untouched', () => {
    const h = workbookHarness(['1', 'Pending edit', '0012']);
    const before = JSON.stringify([h.data.cells, h.meta.cells]);
    let calls = 0;
    h.s.apiRequest = () => ++calls === 1
        ? { code: 200, body: { data: [], schema, next_cursor: 'next' } }
        : { code: 404, body: {} };
    assert.throws(() => h.s.syncSantriSheet(), /Incomplete API page/);
    assert.equal(JSON.stringify([h.data.cells, h.meta.cells]), before);
    assert.equal(h.releases(), 1);
});

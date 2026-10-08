'use client';

import { useState, useEffect, useRef, useCallback, useMemo } from 'react';
import SearchableSelect from './SearchableSelect';

// ─────────────────────────────────────────────
// Types & Constants
// ─────────────────────────────────────────────
type SeederType =
  | 'UpdateTable'
  | 'UpdatePoPrice'
  | 'UpdateWarehouseOfReceivedPO'
  | 'UpdateTableSameColumnMultipleRecords'
  | 'DeleteFasBasketAndRequisitionConnection'
  | 'ManualRevertOfPurchaseBasket'
  | 'PoRecomputeTotalOrAmountToPay';

const SEEDER_TYPES: SeederType[] = [
  'UpdateTable',
  'UpdatePoPrice',
  'UpdateWarehouseOfReceivedPO',
  'UpdateTableSameColumnMultipleRecords',
  'DeleteFasBasketAndRequisitionConnection',
  'ManualRevertOfPurchaseBasket',
  'PoRecomputeTotalOrAmountToPay',
];

interface Toast {
  id: number;
  message: string;
  type: 'success' | 'info' | 'error';
}

interface Fields {
  model: string;
  column: string;
  id: string;
  newValue: string;
  poId: string;
  poItemId: string;
  newDrPrice: string;
  basesOfQuantity: string;
  wantToRecompute: string;
  proceed: string;
  warehouseId: string;
  ids: string;
  fasBasketId: string;
  poIds: string;
  recomputeOption: string;
  quantityBasis: string;
}

const DEFAULT_FIELDS: Fields = {
  model: '',
  column: '',
  id: '',
  newValue: '',
  poId: '',
  poItemId: '',
  newDrPrice: '',
  basesOfQuantity: '1',
  wantToRecompute: 'y',
  proceed: 'y',
  warehouseId: '',
  ids: '',
  fasBasketId: '',
  poIds: '',
  recomputeOption: '2',
  quantityBasis: '1',
};

const FIXED_HEADER = 'Seeder to run: php artisan db:seed BulkUpdateFromText';
let toastIdCounter = 0;

// ─────────────────────────────────────────────
// SeederTool Component
// ─────────────────────────────────────────────
export default function SeederTool({
  initialModels,
  modelCount,
}: {
  initialModels: Record<string, string[]>;
  modelCount: number;
}) {
  const [theme, setTheme] = useState<'dark' | 'light'>('dark');
  const [company, setCompany] = useState<string>('INGCO');
  const [customCompany, setCustomCompany] = useState('');
  const [seederType, setSeederType] = useState<SeederType>('UpdateTable');
  const [fields, setFields] = useState<Fields>(DEFAULT_FIELDS);
  const [notepad, setNotepad] = useState('');
  const [historyStack, setHistoryStack] = useState<string[]>([]);
  const [toasts, setToasts] = useState<Toast[]>([]);
  const [isModalOpen, setIsModalOpen] = useState(false);
  const [modelsData, setModelsData] = useState<Record<string, string[]>>(() =>
    JSON.parse(JSON.stringify(initialModels))
  );
  const defaultModels = useRef(initialModels);
  const [modalModels, setModalModels] = useState<{ name: string; columns: string[] }[]>([]);
  const [addColumnInputs, setAddColumnInputs] = useState<string[]>([]);

  const notepadRef = useRef<HTMLTextAreaElement>(null);
  const initialLoadDone = useRef(false);

  // ── Load from localStorage ────────────────────────────────
  useEffect(() => {
    const savedTheme = localStorage.getItem('seeder_theme');
    if (savedTheme === 'light') setTheme('light');

    const savedNotepad = localStorage.getItem('seeder_notepad_text');
    if (savedNotepad) setNotepad(savedNotepad);

    const savedModels = localStorage.getItem('seeder_models_dictionary');
    if (savedModels) {
      try { setModelsData(JSON.parse(savedModels)); } catch { /* use default */ }
    }

    initialLoadDone.current = true;
  }, []);

  // ── Theme → body class ────────────────────────────────────
  useEffect(() => {
    document.body.classList.remove('dark-mode', 'light-mode');
    document.body.classList.add(`${theme}-mode`);
    localStorage.setItem('seeder_theme', theme);
  }, [theme]);

  // ── Persist notepad ────────────────────────────────────────
  useEffect(() => {
    if (!initialLoadDone.current) return;
    localStorage.setItem('seeder_notepad_text', notepad);
  }, [notepad]);

  // ── Derived ────────────────────────────────────────────────
  const modelNames = useMemo(() => Object.keys(modelsData).sort(), [modelsData]);

  const columnOptions = useMemo(
    () => (fields.model ? modelsData[fields.model] || [] : []),
    [fields.model, modelsData]
  );

  const entryCount = useMemo(() => {
    const text = notepad.trim();
    if (!text) return 0;
    const matches = text.match(/(Seeder:|seeder:)/gi);
    return matches ? matches.length : 1;
  }, [notepad]);

  // ── Toast ──────────────────────────────────────────────────
  const showToast = useCallback((message: string, type: Toast['type'] = 'info') => {
    const id = ++toastIdCounter;
    setToasts(prev => [...prev, { id, message, type }]);
    setTimeout(() => setToasts(prev => prev.filter(t => t.id !== id)), 2400);
  }, []);

  // ── Field helpers ──────────────────────────────────────────
  const setField = useCallback(<K extends keyof Fields>(key: K, val: Fields[K]) => {
    setFields(prev => ({ ...prev, [key]: val }));
  }, []);

  const handleModelChange = useCallback((val: string) => {
    setFields(prev => ({ ...prev, model: val, column: '', id: '', ids: '', newValue: '' }));
  }, []);

  // ── Generate ───────────────────────────────────────────────
  const handleGenerate = useCallback(() => {
    const companyVal = company === 'CUSTOM'
      ? (customCompany.trim() || 'INGCO/WADFOW')
      : company;

    let lines: string[] = [];

    switch (seederType) {
      case 'UpdateTable': {
        if (!fields.model || !fields.column || !fields.id) {
          showToast('Please fill in model, column, and id fields', 'error');
          return;
        }
        lines = [
          companyVal,
          'Seeder: UpdateTable',
          `model: ${fields.model}`,
          `column: ${fields.column}`,
          `id: ${fields.id}`,
          `new value: ${fields.newValue}`,
        ];
        break;
      }
      case 'UpdatePoPrice': {
        lines = [
          companyVal,
          'Seeder: UpdatePoPrice',
          `po id: ${fields.poId}`,
          `po item id : ${fields.poItemId}`,
          `new dr price: ${fields.newDrPrice}`,
          `bases of quantity [0/1]: ${fields.basesOfQuantity}`,
          `want to recompute item amount to pay?: ${fields.wantToRecompute}`,
          `proceed?: ${fields.proceed}`,
        ];
        break;
      }
      case 'UpdateWarehouseOfReceivedPO': {
        lines = [
          companyVal,
          'Seeder: UpdateWarehouseOfReceivedPO',
          `po_id: ${fields.poId}`,
          `warehouse_id: ${fields.warehouseId}`,
        ];
        break;
      }
      case 'UpdateTableSameColumnMultipleRecords': {
        lines = [
          companyVal,
          'Seeder: UpdateTableSameColumnMultipleRecords',
          `model: ${fields.model}`,
          `column: ${fields.column}`,
          `ids: ${fields.ids}`,
          `new value: ${fields.newValue}`,
        ];
        break;
      }
      case 'DeleteFasBasketAndRequisitionConnection': {
        lines = [
          companyVal,
          'Seeder: DeleteFasBasketAndRequisitionConnection',
          `admin_fas_basket_id: ${fields.fasBasketId}`,
        ];
        break;
      }
      case 'ManualRevertOfPurchaseBasket': {
        lines = [
          companyVal,
          'seeder: ManualRevertOfPurchaseBasket',
          `ids: ${fields.ids}`,
        ];
        break;
      }
      case 'PoRecomputeTotalOrAmountToPay': {
        lines = [
          companyVal,
          'Seeder: PoRecomputeTotalOrAmountToPay',
          `Po Ids: ${fields.poIds}`,
          `What do you want to recompute?: ${fields.recomputeOption}`,
          `What quantity will be the basis for recompute?: ${fields.quantityBasis}`,
          `Proceed with recompute?: ${fields.proceed}`,
        ];
        break;
      }
    }

    const chunk = lines.join('\n');
    setHistoryStack(prev => [...prev, notepad]);
    setNotepad(prev => prev.trim() ? prev + '\n\n' + chunk : chunk);
    showToast(`Appended ${seederType} to notepad!`, 'success');

    setTimeout(() => {
      if (notepadRef.current) notepadRef.current.scrollTop = notepadRef.current.scrollHeight;
    }, 50);
  }, [company, customCompany, seederType, fields, notepad, showToast]);

  // ── Keyboard shortcut Ctrl+Enter ──────────────────────────
  useEffect(() => {
    const handler = (e: KeyboardEvent) => {
      if ((e.ctrlKey || e.metaKey) && e.key === 'Enter') {
        e.preventDefault();
        handleGenerate();
      }
    };
    document.addEventListener('keydown', handler);
    return () => document.removeEventListener('keydown', handler);
  }, [handleGenerate]);

  // ── Notepad actions ────────────────────────────────────────
  const fullContent = () => {
    return notepad.trim() ? FIXED_HEADER + '\n\n' + notepad : FIXED_HEADER;
  };

  const handleCopyAll = () => {
    if (!notepad.trim()) { showToast('Notepad is empty!', 'error'); return; }
    const text = fullContent();
    if (navigator.clipboard && window.isSecureContext) {
      navigator.clipboard.writeText(text)
        .then(() => showToast('Copied to clipboard!', 'success'))
        .catch(() => showToast('Copy failed', 'error'));
    } else {
      const tmp = document.createElement('textarea');
      tmp.value = text;
      Object.assign(tmp.style, { position: 'fixed', opacity: '0' });
      document.body.appendChild(tmp);
      tmp.select();
      document.execCommand('copy');
      document.body.removeChild(tmp);
      showToast('Copied to clipboard!', 'success');
    }
  };

  const handleDownload = () => {
    if (!notepad.trim()) { showToast('Notepad is empty!', 'error'); return; }
    const blob = new Blob([fullContent()], { type: 'text/plain;charset=utf-8' });
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = `seeders-${new Date().toISOString().slice(0, 10)}.txt`;
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
    URL.revokeObjectURL(url);
    showToast('Downloaded as .txt file', 'success');
  };

  const handleUndo = () => {
    if (historyStack.length === 0) return;
    const prev = historyStack[historyStack.length - 1];
    setHistoryStack(h => h.slice(0, -1));
    setNotepad(prev);
    showToast('Undid last generated entry', 'info');
  };

  const handleClear = () => {
    if (!notepad.trim()) return;
    if (confirm('Are you sure you want to clear the notepad?')) {
      setHistoryStack(h => [...h, notepad]);
      setNotepad('');
      showToast('Notepad cleared', 'info');
    }
  };

  const handleResetForm = () => {
    setCompany('INGCO');
    setCustomCompany('');
    setSeederType('UpdateTable');
    setFields(DEFAULT_FIELDS);
    showToast('Form reset to default values', 'info');
  };

  // ── Modal ──────────────────────────────────────────────────
  const openModal = () => {
    const entries = Object.entries(modelsData)
      .sort(([a], [b]) => a.localeCompare(b))
      .map(([name, columns]) => ({ name, columns: [...columns] }));
    setModalModels(entries);
    setAddColumnInputs(entries.map(() => ''));
    setIsModalOpen(true);
  };

  const closeModal = () => setIsModalOpen(false);

  const handleSaveModels = () => {
    const dict: Record<string, string[]> = {};
    modalModels.forEach(({ name, columns }) => {
      if (name.trim()) dict[name.trim()] = columns;
    });
    setModelsData(dict);
    localStorage.setItem('seeder_models_dictionary', JSON.stringify(dict));
    closeModal();
    showToast('Saved Models & Columns dictionary!', 'success');
  };

  const handleResetModels = () => {
    if (confirm('Reset models to PHP reference defaults?')) {
      const fresh = JSON.parse(JSON.stringify(defaultModels.current));
      setModelsData(fresh);
      localStorage.setItem('seeder_models_dictionary', JSON.stringify(fresh));
      const entries = Object.entries(fresh)
        .sort(([a], [b]) => a.localeCompare(b))
        .map(([name, columns]: [string, string[]]) => ({ name, columns: [...columns] }));
      setModalModels(entries);
      setAddColumnInputs(entries.map(() => ''));
      showToast(`Reset to ${modelCount} PHP reference models`, 'info');
    }
  };

  const handleAddNewModel = () => {
    const newName = prompt('Enter new Model Name (e.g. AdminCustomer):');
    if (newName?.trim()) {
      const trimmed = newName.trim();
      if (modalModels.some(m => m.name === trimmed)) {
        showToast('Model already exists', 'error');
      } else {
        setModalModels(prev => [...prev, { name: trimmed, columns: ['id', 'created_at', 'updated_at'] }]);
        setAddColumnInputs(prev => [...prev, '']);
      }
    }
  };

  const addModalColumn = (idx: number) => {
    const val = addColumnInputs[idx]?.trim();
    if (!val) return;
    setModalModels(prev => prev.map((m, i) => {
      if (i !== idx) return m;
      if (m.columns.includes(val)) return m;
      return { ...m, columns: [...m.columns, val] };
    }));
    setAddColumnInputs(prev => prev.map((v, i) => i === idx ? '' : v));
  };

  // ── Dynamic fields ──────────────────────────────────────────
  const renderDynamicFields = () => {
    switch (seederType) {
      case 'UpdateTable':
        return (
          <>
            <div className="form-group">
              <label className="form-label">model <span className="required">*</span></label>
              <SearchableSelect
                options={modelNames}
                value={fields.model}
                placeholder={`Search model (${modelCount} models)...`}
                allowCustom
                onChange={val => handleModelChange(val)}
              />
            </div>
            <div className="form-group">
              <label className="form-label">column <span className="required">*</span></label>
              <SearchableSelect
                options={columnOptions}
                value={fields.column}
                placeholder={fields.model ? 'Search column...' : 'Select a model first...'}
                allowCustom
                onChange={val => setField('column', val)}
              />
            </div>
            <div className="form-group">
              <label className="form-label">id <span className="required">*</span></label>
              <input className="form-control" placeholder="e.g. 463"
                value={fields.id} onChange={e => setField('id', e.target.value)} />
            </div>
            <div className="form-group">
              <label className="form-label">new value <span className="required">*</span></label>
              <input className="form-control" placeholder="e.g. NULL or 2"
                value={fields.newValue} onChange={e => setField('newValue', e.target.value)} />
              <div className="field-presets">
                {['NULL', '1', '0', '2', 'NOW()'].map(v => (
                  <span key={v} className="preset-chip" onClick={() => setField('newValue', v)}>{v}</span>
                ))}
              </div>
            </div>
          </>
        );

      case 'UpdatePoPrice':
        return (
          <>
            <div className="form-group">
              <label className="form-label">po id <span className="required">*</span></label>
              <input className="form-control" placeholder="e.g. 6419"
                value={fields.poId} onChange={e => setField('poId', e.target.value)} />
            </div>
            <div className="form-group">
              <label className="form-label">po item id <span className="required">*</span></label>
              <input className="form-control" placeholder="e.g. 1024"
                value={fields.poItemId} onChange={e => setField('poItemId', e.target.value)} />
            </div>
            <div className="form-group">
              <label className="form-label">new dr price <span className="required">*</span></label>
              <input className="form-control" placeholder="e.g. 150.00"
                value={fields.newDrPrice} onChange={e => setField('newDrPrice', e.target.value)} />
            </div>
            <div className="form-group">
              <label className="form-label">bases of quantity [0/1]</label>
              <select className="form-control" value={fields.basesOfQuantity}
                onChange={e => setField('basesOfQuantity', e.target.value)}>
                <option value="1">1</option>
                <option value="0">0</option>
              </select>
            </div>
            <div className="form-group">
              <label className="form-label">want to recompute item amount to pay?</label>
              <select className="form-control" value={fields.wantToRecompute}
                onChange={e => setField('wantToRecompute', e.target.value)}>
                <option value="y">y</option>
                <option value="n">n</option>
              </select>
            </div>
            <div className="form-group">
              <label className="form-label">proceed?</label>
              <select className="form-control" value={fields.proceed}
                onChange={e => setField('proceed', e.target.value)}>
                <option value="y">y</option>
                <option value="n">n</option>
              </select>
            </div>
          </>
        );

      case 'UpdateWarehouseOfReceivedPO':
        return (
          <>
            <div className="form-group">
              <label className="form-label">po_id <span className="required">*</span></label>
              <input className="form-control" placeholder="e.g. 8299"
                value={fields.poId} onChange={e => setField('poId', e.target.value)} />
            </div>
            <div className="form-group">
              <label className="form-label">warehouse_id <span className="required">*</span></label>
              <input className="form-control" placeholder="e.g. 12"
                value={fields.warehouseId} onChange={e => setField('warehouseId', e.target.value)} />
            </div>
          </>
        );

      case 'UpdateTableSameColumnMultipleRecords':
        return (
          <>
            <div className="form-group">
              <label className="form-label">model <span className="required">*</span></label>
              <SearchableSelect
                options={modelNames}
                value={fields.model}
                placeholder={`Search model (${modelCount} models)...`}
                allowCustom
                onChange={val => handleModelChange(val)}
              />
            </div>
            <div className="form-group">
              <label className="form-label">column <span className="required">*</span></label>
              <SearchableSelect
                options={columnOptions}
                value={fields.column}
                placeholder={fields.model ? 'Search column...' : 'Select a model first...'}
                allowCustom
                onChange={val => setField('column', val)}
              />
            </div>
            <div className="form-group">
              <label className="form-label">ids <span className="required">*</span></label>
              <input className="form-control" placeholder="e.g. 101, 102, 103"
                value={fields.ids} onChange={e => setField('ids', e.target.value)} />
            </div>
            <div className="form-group">
              <label className="form-label">new value <span className="required">*</span></label>
              <input className="form-control" placeholder="e.g. 2"
                value={fields.newValue} onChange={e => setField('newValue', e.target.value)} />
              <div className="field-presets">
                {['NULL', '1', '0', '2'].map(v => (
                  <span key={v} className="preset-chip" onClick={() => setField('newValue', v)}>{v}</span>
                ))}
              </div>
            </div>
          </>
        );

      case 'DeleteFasBasketAndRequisitionConnection':
        return (
          <div className="form-group">
            <label className="form-label">admin_fas_basket_id <span className="required">*</span></label>
            <input className="form-control" placeholder="e.g. 5962"
              value={fields.fasBasketId} onChange={e => setField('fasBasketId', e.target.value)} />
          </div>
        );

      case 'ManualRevertOfPurchaseBasket':
        return (
          <div className="form-group">
            <label className="form-label">ids <span className="required">*</span></label>
            <input className="form-control" placeholder="e.g. 5962"
              value={fields.ids} onChange={e => setField('ids', e.target.value)} />
          </div>
        );

      case 'PoRecomputeTotalOrAmountToPay':
        return (
          <>
            <div className="form-group">
              <label className="form-label">Po Ids <span className="required">*</span></label>
              <input className="form-control" placeholder="e.g. 8299"
                value={fields.poIds} onChange={e => setField('poIds', e.target.value)} />
            </div>
            <div className="form-group">
              <label className="form-label">What do you want to recompute?</label>
              <select className="form-control" value={fields.recomputeOption}
                onChange={e => setField('recomputeOption', e.target.value)}>
                <option value="1">1 - Recompute Total</option>
                <option value="2">2 - Recompute Amount to Pay</option>
                <option value="3">3 - Both</option>
              </select>
            </div>
            <div className="form-group">
              <label className="form-label">What quantity will be the basis for recompute?</label>
              <select className="form-control" value={fields.quantityBasis}
                onChange={e => setField('quantityBasis', e.target.value)}>
                <option value="1">1</option>
                <option value="0">0</option>
              </select>
            </div>
            <div className="form-group">
              <label className="form-label">Proceed with recompute?</label>
              <select className="form-control" value={fields.proceed}
                onChange={e => setField('proceed', e.target.value)}>
                <option value="y">y</option>
                <option value="n">n</option>
              </select>
            </div>
          </>
        );

      default:
        return null;
    }
  };

  // ── Render ────────────────────────────────────────────────
  return (
    <div className="app-container">
      {/* Header */}
      <header className="app-header">
        <div className="brand">
          <div className="logo-icon">⚡</div>
          <div className="brand-text">
            <h1>Seeder AutoFill Tool</h1>
            <span className="badge">v2.0</span>
          </div>
        </div>
        <div className="header-actions">
          <button className="btn btn-secondary" onClick={openModal}>
            Models &amp; Columns
          </button>
          <button
            className="btn btn-icon"
            onClick={() => setTheme(t => t === 'dark' ? 'light' : 'dark')}
            title="Toggle theme"
          >
            <span className="sun-icon">☀️</span>
            <span className="moon-icon">🌙</span>
          </button>
        </div>
      </header>

      {/* Workspace */}
      <main className="workspace">
        {/* LEFT: Generator */}
        <section className="panel generator-panel">
          <div className="panel-header">
            <div className="panel-title"><h2>Seeder Generator</h2></div>
            <button className="btn btn-sm btn-ghost" onClick={handleResetForm}>Reset</button>
          </div>
          <form
            className="generator-form"
            onSubmit={e => { e.preventDefault(); handleGenerate(); }}
          >
            {/* Company */}
            <div className="form-group">
              <label className="form-label">Company <span className="required">*</span></label>
              <div className="company-btn-group">
                {(['INGCO', 'WADFOW'] as const).map(c => (
                  <button
                    key={c}
                    type="button"
                    className={`company-btn company-btn-${c.toLowerCase()} ${company === c ? 'active' : ''}`}
                    onClick={() => { setCompany(c); setCustomCompany(''); }}
                  >
                    {c}
                  </button>
                ))}
                <button
                  type="button"
                  className={`company-btn company-btn-custom ${company === 'CUSTOM' ? 'active' : ''}`}
                  onClick={() => setCompany('CUSTOM')}
                >
                  Custom
                </button>
              </div>
              {company === 'CUSTOM' && (
                <input
                  className="form-control"
                  style={{ marginTop: '8px' }}
                  placeholder="Enter custom prefix..."
                  value={customCompany}
                  onChange={e => setCustomCompany(e.target.value)}
                  autoFocus
                />
              )}
            </div>

            {/* Seeder Type */}
            <div className="form-group">
              <label className="form-label">Seeder Type <span className="required">*</span></label>
              <SearchableSelect
                options={SEEDER_TYPES}
                value={seederType}
                placeholder="Search seeder type..."
                allowCustom={false}
                onChange={(val, isSelection) => {
                  if (isSelection || SEEDER_TYPES.includes(val as SeederType)) {
                    setSeederType(val as SeederType);
                    setFields(DEFAULT_FIELDS);
                  }
                }}
              />
            </div>

            <div className="divider" />

            {/* Dynamic Fields */}
            <div className="dynamic-fields">
              {renderDynamicFields()}
            </div>

            {/* Generate Button */}
            <div className="form-action-bar">
              <button type="submit" className="btn btn-primary btn-block btn-lg">
                <span>Generate &amp; Append</span>
                <kbd className="hotkey-badge">Ctrl + Enter</kbd>
              </button>
            </div>
          </form>
        </section>

        {/* RIGHT: Notepad */}
        <section className="panel notepad-panel">
          <div className="panel-header">
            <div className="panel-title">
              <h2>Generated Seeders Notepad</h2>
              <span className="counter-badge">
                {entryCount} {entryCount === 1 ? 'entry' : 'entries'}
              </span>
            </div>
            <div className="panel-actions">
              <button className="btn btn-sm btn-primary" onClick={handleCopyAll}>Copy</button>
              <button className="btn btn-sm btn-secondary" onClick={handleDownload}>Download</button>
              <button className="btn btn-sm btn-secondary" onClick={handleUndo}
                disabled={historyStack.length === 0}>Undo</button>
              <button className="btn btn-sm btn-danger-ghost" onClick={handleClear}>Clear</button>
            </div>
          </div>

          {/* Locked header */}
          <div className="notepad-fixed-header">
            <span className="fixed-header-text">{FIXED_HEADER}</span>
            <span className="fixed-header-lock">🔒 locked</span>
          </div>

          <div className="notepad-wrapper">
            <textarea
              ref={notepadRef}
              className="notepad-textarea"
              placeholder={`Generated seeder outputs will appear here...\n\nYou can also directly edit or add notes manually in this notepad.`}
              spellCheck={false}
              value={notepad}
              onChange={e => setNotepad(e.target.value)}
            />
          </div>
        </section>
      </main>

      {/* Modal: Manage Models */}
      {isModalOpen && (
        <div
          className="modal-backdrop"
          onClick={e => { if (e.target === e.currentTarget) closeModal(); }}
        >
          <div className="modal-card">
            <div className="modal-header">
              <div className="modal-title"><h3>Manage Models &amp; Columns</h3></div>
              <button className="btn btn-icon modal-close" onClick={closeModal}>&times;</button>
            </div>
            <div className="modal-body">
              <div className="modal-toolbar">
                <button className="btn btn-sm btn-primary" onClick={handleAddNewModel}>
                  + Add New Model
                </button>
                <button className="btn btn-sm btn-secondary" onClick={handleResetModels}>
                  Reset to Reference Defaults
                </button>
              </div>
              <div className="model-list">
                {modalModels.map((entry, idx) => (
                  <div key={idx} className="model-item-card">
                    <div className="model-item-header">
                      <input
                        type="text"
                        className="form-control model-name-input"
                        value={entry.name}
                        onChange={e =>
                          setModalModels(prev =>
                            prev.map((m, i) => i === idx ? { ...m, name: e.target.value } : m)
                          )
                        }
                      />
                      <button
                        className="btn btn-sm btn-danger-ghost"
                        onClick={() => {
                          setModalModels(prev => prev.filter((_, i) => i !== idx));
                          setAddColumnInputs(prev => prev.filter((_, i) => i !== idx));
                        }}
                      >
                        Remove
                      </button>
                    </div>
                    <div className="column-tags-wrapper">
                      {entry.columns.map(col => (
                        <span key={col} className="column-tag">
                          {col}
                          <button
                            onClick={() =>
                              setModalModels(prev =>
                                prev.map((m, i) =>
                                  i === idx
                                    ? { ...m, columns: m.columns.filter(c => c !== col) }
                                    : m
                                )
                              )
                            }
                          >
                            &times;
                          </button>
                        </span>
                      ))}
                    </div>
                    <div className="add-column-form">
                      <input
                        type="text"
                        className="form-control add-column-input"
                        placeholder="+ Add column..."
                        value={addColumnInputs[idx] || ''}
                        onChange={e =>
                          setAddColumnInputs(prev =>
                            prev.map((v, i) => i === idx ? e.target.value : v)
                          )
                        }
                        onKeyDown={e => { if (e.key === 'Enter') addModalColumn(idx); }}
                      />
                      <button
                        className="btn btn-sm btn-secondary"
                        onClick={() => addModalColumn(idx)}
                      >
                        Add
                      </button>
                    </div>
                  </div>
                ))}
              </div>
            </div>
            <div className="modal-footer">
              <button className="btn btn-primary" onClick={handleSaveModels}>
                Save Changes
              </button>
            </div>
          </div>
        </div>
      )}

      {/* Toasts */}
      <div className="toast-container">
        {toasts.map(t => (
          <div key={t.id} className={`toast ${t.type}`}>
            <span>{t.message}</span>
          </div>
        ))}
      </div>
    </div>
  );
}

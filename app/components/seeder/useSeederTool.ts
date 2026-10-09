'use client';

import { useState, useEffect, useRef, useCallback, useMemo } from 'react';
import {
  SeederType, Fields, Toast,
  DEFAULT_FIELDS, FIXED_HEADER, SEEDER_TYPES,
} from './types';

// Module-level counter so IDs never reset between renders
let toastIdCounter = 0;

// ─────────────────────────────────────────────
// useSeederTool — all state & handlers, zero JSX
// ─────────────────────────────────────────────
export function useSeederTool(
  initialModels: Record<string, string[]>,
  modelCount: number,
) {
  // ── Core UI state ──────────────────────────────────────────
  const [theme, setTheme] = useState<'dark' | 'light'>('dark');

  // ── Company ────────────────────────────────────────────────
  const [company, setCompany] = useState<string>('INGCO');
  const [customCompany, setCustomCompany] = useState('');

  // ── Generator form ─────────────────────────────────────────
  const [seederType, setSeederType] = useState<SeederType>('UpdateTable');
  const [fields, setFields] = useState<Fields>(DEFAULT_FIELDS);

  // ── Notepad ────────────────────────────────────────────────
  const [notepad, setNotepad] = useState('');
  const [historyStack, setHistoryStack] = useState<string[]>([]);
  const notepadRef = useRef<HTMLTextAreaElement>(null);
  const csvImportRef = useRef<HTMLInputElement>(null);

  // ── Models dictionary ──────────────────────────────────────
  const [modelsData, setModelsData] = useState<Record<string, string[]>>(() =>
    JSON.parse(JSON.stringify(initialModels))
  );
  const defaultModels = useRef(initialModels);

  // ── Models modal open/close ────────────────────────────────
  const [isModelsModalOpen, setIsModelsModalOpen] = useState(false);

  // ── Toasts ─────────────────────────────────────────────────
  const [toasts, setToasts] = useState<Toast[]>([]);

  // ── Init flag ─────────────────────────────────────────────
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

  // ── Derived ───────────────────────────────────────────────
  const modelNames = useMemo(() => Object.keys(modelsData).sort(), [modelsData]);

  const columnOptions = useMemo(
    () => (fields.model ? modelsData[fields.model] || [] : []),
    [fields.model, modelsData],
  );

  const entryCount = useMemo(() => {
    const text = notepad.trim();
    if (!text) return 0;
    const matches = text.match(/(Seeder:|seeder:)/gi);
    return matches ? matches.length : 1;
  }, [notepad]);

  // ── Toast ─────────────────────────────────────────────────
  const showToast = useCallback((message: string, type: Toast['type'] = 'info') => {
    const id = ++toastIdCounter;
    setToasts(prev => [...prev, { id, message, type }]);
    setTimeout(() => setToasts(prev => prev.filter(t => t.id !== id)), 2400);
  }, []);

  // ── Field helpers ─────────────────────────────────────────
  const setField = useCallback(<K extends keyof Fields>(key: K, val: Fields[K]) => {
    setFields(prev => ({ ...prev, [key]: val }));
  }, []);

  const handleModelChange = useCallback((val: string) => {
    setFields(prev => ({ ...prev, model: val, column: '', id: '', ids: '', newValue: '' }));
  }, []);

  // ── Generate ──────────────────────────────────────────────
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
      case 'RenameConsolidatedAdminItems': {
        lines = [
          companyVal,
          'Seeder: RenameConsolidatedAdminItems',
          `ids: ${fields.ids}`,
          `proceed (yes/no): ${fields.proceed}`,
        ];
        break;
      }
      case 'RevertFasBasketPoCreationSeeder': {
        lines = [
          companyVal,
          'Seeder: RevertFasBasketPoCreationSeeder',
          `PB# : ${fields.pbNumber}`,
          `Proceed: ${fields.proceed}`,
        ];
        break;
      }
      case 'UndoReceivedPOSeeder': {
        lines = [
          companyVal,
          'Seeder: UndoReceivedPOSeeder',
          `undo PO received: ${fields.undoPoReceived}`,
          `PO id: ${fields.poId}`,
          `proceed (yes/no): ${fields.proceed}`,
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

  // ── Ctrl+Enter shortcut ───────────────────────────────────
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

  // ── Notepad actions ───────────────────────────────────────
  const fullContent = useCallback(() =>
    notepad.trim() ? FIXED_HEADER + '\n\n' + notepad : FIXED_HEADER,
  [notepad]);

  const handleCopyAll = useCallback(() => {
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
  }, [notepad, fullContent, showToast]);

  const handleDownload = useCallback(() => {
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
  }, [notepad, fullContent, showToast]);

  const handleUndo = useCallback(() => {
    if (historyStack.length === 0) return;
    const prev = historyStack[historyStack.length - 1];
    setHistoryStack(h => h.slice(0, -1));
    setNotepad(prev);
    showToast('Undid last generated entry', 'info');
  }, [historyStack, showToast]);

  const handleAddBreak = useCallback(() => {
    if (!notepad.trim()) { showToast('Notepad is empty!', 'error'); return; }
    setHistoryStack(prev => [...prev, notepad]);
    setNotepad(prev => prev.trimEnd() + '\n\nEND');
    showToast('Break added', 'info');
    setTimeout(() => {
      if (notepadRef.current) notepadRef.current.scrollTop = notepadRef.current.scrollHeight;
    }, 50);
  }, [notepad, notepadRef, showToast]);

  const handleClear = useCallback(() => {
    if (!notepad.trim()) return;
    if (confirm('Are you sure you want to clear the notepad?')) {
      setHistoryStack(h => [...h, notepad]);
      setNotepad('');
      showToast('Notepad cleared', 'info');
    }
  }, [notepad, showToast]);

  const handleSeederTypeChange = useCallback((type: SeederType) => {
    setSeederType(type);
    setFields(DEFAULT_FIELDS);
  }, []);

  const handleResetForm = useCallback(() => {
    setCompany('INGCO');
    setCustomCompany('');
    setSeederType('' as SeederType);
    setFields(DEFAULT_FIELDS);
    showToast('Form reset to default values', 'info');
  }, [showToast]);

  // ── CSV Import ────────────────────────────────────────────
  const handleCsvFileChange = useCallback((e: React.ChangeEvent<HTMLInputElement>) => {
    const file = e.target.files?.[0];
    if (!file) return;
    e.target.value = '';

    const reader = new FileReader();
    reader.onload = (ev) => {
      const text = ev.target?.result as string;
      if (!text) { showToast('Empty file', 'error'); return; }

      const lines = text.split(/\r?\n/).filter(l => l.trim());
      if (lines.length < 2) {
        showToast('CSV must have a header row + at least one data row', 'error');
        return;
      }

      const headers = lines[0].split(',').map(h => h.trim().toLowerCase());
      const chunks: string[] = [];

      for (let i = 1; i < lines.length; i++) {
        const raw = lines[i].split(',').map(c => c.trim());
        const row: Record<string, string> = {};
        headers.forEach((h, idx) => { row[h] = raw[idx] ?? ''; });

        const companyVal = row['company'] || company;
        const csvSeeder = (row['seeder'] || row['seeder_type'] || '').trim();
        const detectedType = (csvSeeder && SEEDER_TYPES.includes(csvSeeder as SeederType))
          ? csvSeeder as SeederType
          : seederType;

        let chunk = '';
        switch (detectedType) {
          case 'UpdateTable':
            chunk = [
              companyVal, 'Seeder: UpdateTable',
              `model: ${row['model'] || ''}`,
              `column: ${row['column'] || ''}`,
              `id: ${row['id'] || ''}`,
              `new value: ${row['new_value'] ?? row['newvalue'] ?? row['value'] ?? ''}`,
            ].join('\n');
            break;
          case 'UpdatePoPrice':
            chunk = [
              companyVal, 'Seeder: UpdatePoPrice',
              `po id: ${row['po_id'] ?? row['poid'] ?? ''}`,
              `po item id : ${row['po_item_id'] ?? row['poitemid'] ?? ''}`,
              `new dr price: ${row['new_dr_price'] ?? row['drprice'] ?? ''}`,
              `bases of quantity [0/1]: ${row['bases_of_quantity'] ?? '1'}`,
              `want to recompute item amount to pay?: ${row['want_to_recompute'] ?? 'y'}`,
              `proceed?: ${row['proceed'] ?? 'y'}`,
            ].join('\n');
            break;
          case 'UpdateWarehouseOfReceivedPO':
            chunk = [
              companyVal, 'Seeder: UpdateWarehouseOfReceivedPO',
              `po_id: ${row['po_id'] ?? row['poid'] ?? ''}`,
              `warehouse_id: ${row['warehouse_id'] ?? row['warehouseid'] ?? ''}`,
            ].join('\n');
            break;
          case 'UpdateTableSameColumnMultipleRecords':
            chunk = [
              companyVal, 'Seeder: UpdateTableSameColumnMultipleRecords',
              `model: ${row['model'] || ''}`,
              `column: ${row['column'] || ''}`,
              `ids: ${row['ids'] || ''}`,
              `new value: ${row['new_value'] ?? row['value'] ?? ''}`,
            ].join('\n');
            break;
          case 'ManualRevertOfPurchaseBasket':
            chunk = [
              companyVal, 'seeder: ManualRevertOfPurchaseBasket',
              `ids: ${row['ids'] || ''}`,
            ].join('\n');
            break;
          case 'PoRecomputeTotalOrAmountToPay':
            chunk = [
              companyVal, 'Seeder: PoRecomputeTotalOrAmountToPay',
              `Po Ids: ${row['po_ids'] ?? row['poids'] ?? ''}`,
              `What do you want to recompute?: ${row['recompute_option'] ?? '2'}`,
              `What quantity will be the basis for recompute?: ${row['quantity_basis'] ?? '1'}`,
              `Proceed with recompute?: ${row['proceed'] ?? 'y'}`,
            ].join('\n');
            break;
          case 'DeleteFasBasketAndRequisitionConnection':
            chunk = [
              companyVal, 'Seeder: DeleteFasBasketAndRequisitionConnection',
              `admin_fas_basket_id: ${row['fas_basket_id'] ?? row['fasbasketid'] ?? ''}`,
            ].join('\n');
            break;
          case 'RenameConsolidatedAdminItems':
            chunk = [
              companyVal, 'Seeder: RenameConsolidatedAdminItems',
              `ids: ${row['ids'] || ''}`,
              `proceed (yes/no): ${row['proceed'] || 'yes'}`,
            ].join('\n');
            break;
          case 'RevertFasBasketPoCreationSeeder':
            chunk = [
              companyVal, 'Seeder: RevertFasBasketPoCreationSeeder',
              `PB# : ${row['pb_number'] ?? row['pbnumber'] ?? row['pb'] ?? ''}`,
              `Proceed: ${row['proceed'] ?? 'yes'}`,
            ].join('\n');
            break;
          case 'UndoReceivedPOSeeder':
            chunk = [
              companyVal, 'Seeder: UndoReceivedPOSeeder',
              `undo PO received: ${row['undo_po_received'] ?? '1'}`,
              `PO id: ${row['po_id'] ?? row['poid'] ?? ''}`,
              `proceed (yes/no): ${row['proceed'] ?? 'yes'}`,
            ].join('\n');
            break;
          default:
            chunk = [
              companyVal,
              ...headers
                .filter(h => h !== 'company' && h !== 'seeder' && h !== 'seeder_type')
                .map(h => `${h}: ${row[h]}`),
            ].join('\n');
        }
        chunks.push(chunk);
      }

      if (chunks.length === 0) { showToast('No valid rows found in CSV', 'error'); return; }

      setHistoryStack(prev => [...prev, notepad]);
      setNotepad(prev =>
        prev.trim() ? prev + '\n\n' + chunks.join('\n\n') : chunks.join('\n\n')
      );
      showToast(`Imported ${chunks.length} row${chunks.length > 1 ? 's' : ''} from CSV!`, 'success');

      setTimeout(() => {
        if (notepadRef.current) notepadRef.current.scrollTop = notepadRef.current.scrollHeight;
      }, 50);
    };
    reader.readAsText(file);
  }, [company, seederType, notepad, showToast]);

  // ── Models modal callbacks ────────────────────────────────
  const openModelsModal = useCallback(() => setIsModelsModalOpen(true), []);
  const closeModelsModal = useCallback(() => setIsModelsModalOpen(false), []);

  /** Called by ModelsModal when user clicks Save Changes */
  const handleSaveModels = useCallback((dict: Record<string, string[]>) => {
    setModelsData(dict);
    localStorage.setItem('seeder_models_dictionary', JSON.stringify(dict));
    fetch('/api/models', {
      method: 'PUT',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(dict),
    }).catch(() => { /* offline / no DB */ });
    closeModelsModal();
    showToast('Saved Models & Columns dictionary!', 'success');
  }, [closeModelsModal, showToast]);

  /** Called by ModelsModal "Reset to Defaults" — commits defaults and returns the fresh dict */
  const handleResetToDefaults = useCallback((): Record<string, string[]> => {
    const fresh: Record<string, string[]> = JSON.parse(JSON.stringify(defaultModels.current));
    setModelsData(fresh);
    localStorage.setItem('seeder_models_dictionary', JSON.stringify(fresh));
    showToast(`Reset to ${modelCount} PHP reference models`, 'info');
    return fresh;
  }, [modelCount, showToast]);

  // ─────────────────────────────────────────────────────────
  return {
    // theme
    theme, setTheme,
    // company
    company, setCompany, customCompany, setCustomCompany,
    // form
    seederType, setSeederType, handleSeederTypeChange,
    fields, setField, handleModelChange,
    handleGenerate, handleResetForm,
    // models
    modelsData, modelNames, columnOptions,
    isModelsModalOpen, openModelsModal, closeModelsModal,
    handleSaveModels, handleResetToDefaults,
    // notepad
    notepad, setNotepad, notepadRef,
    historyStack, entryCount,
    handleCopyAll, handleDownload, handleUndo, handleClear, handleAddBreak,
    // csv
    csvImportRef, handleCsvFileChange,
    // toasts
    toasts, showToast,
  };
}

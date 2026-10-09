'use client';

import { useState } from 'react';
import { Toast } from '../types';
import AddModelDialog from './AddModelDialog';
import { PlusIcon, SearchIcon } from 'lucide-react';

interface ModalModel {
  name: string;
  columns: string[];
}

interface ModelsModalProps {
  /** Snapshot of modelsData at the moment the modal was opened */
  initialSnapshot: Record<string, string[]>;
  /** The original PHP-reference defaults; used for "Reset to Defaults" */
  defaultSnapshot: Record<string, string[]>;
  modelCount: number;
  onSave: (dict: Record<string, string[]>) => void;
  onClose: () => void;
  /** Called when user confirms Reset — commits defaults externally AND returns the fresh dict */
  onResetToDefaults: () => Record<string, string[]>;
  showToast: (msg: string, type: Toast['type']) => void;
}

// ─────────────────────────────────────────────
// ModelsModal — Manage Models & Columns
// ─────────────────────────────────────────────
export default function ModelsModal({
  initialSnapshot,
  modelCount,
  onSave,
  onClose,
  onResetToDefaults,
  showToast,
}: ModelsModalProps) {
  // ── Working state (local copy, not committed until Save) ───
  const [modalModels, setModalModels] = useState<ModalModel[]>(() =>
    Object.entries(initialSnapshot)
      .sort(([a], [b]) => a.localeCompare(b))
      .map(([name, columns]) => ({ name, columns: [...columns] }))
  );
  const [addColumnInputs, setAddColumnInputs] = useState<string[]>(() =>
    Object.keys(initialSnapshot).map(() => '')
  );

  // ── Search ─────────────────────────────────────────────────
  const [modelSearch, setModelSearch] = useState('');

  // ── Add-model dialog ───────────────────────────────────────
  const [isAddDialogOpen, setIsAddDialogOpen] = useState(false);

  // ── Derived: filtered view with original indices preserved ─
  const indexedModels = modalModels.map((m, idx) => ({ ...m, idx }));
  const visibleModels = modelSearch.trim()
    ? indexedModels.filter(m =>
        m.name.toLowerCase().includes(modelSearch.trim().toLowerCase())
      )
    : indexedModels;

  // ── Handlers ───────────────────────────────────────────────
  const handleSave = () => {
    const dict: Record<string, string[]> = {};
    modalModels.forEach(({ name, columns }) => {
      if (name.trim()) dict[name.trim()] = columns;
    });
    onSave(dict);
  };

  const handleResetModels = () => {
    if (!confirm('Reset models to PHP reference defaults?')) return;
    const fresh = onResetToDefaults();
    const entries = Object.entries(fresh)
      .sort(([a], [b]) => a.localeCompare(b))
      .map(([name, columns]) => ({ name, columns: [...columns] }));
    setModalModels(entries);
    setAddColumnInputs(entries.map(() => ''));
    setModelSearch('');
  };

  const handleConfirmAddModel = (name: string, columns: string[]) => {
    if (modalModels.some(m => m.name === name)) {
      showToast('Model already exists', 'error');
      return;
    }
    setModalModels(prev => [...prev, { name, columns }]);
    setAddColumnInputs(prev => [...prev, '']);
    setIsAddDialogOpen(false);
    showToast(`Model "${name}" added`, 'success');
  };

  const removeModel = (originalIdx: number) => {
    setModalModels(prev => prev.filter((_, i) => i !== originalIdx));
    setAddColumnInputs(prev => prev.filter((_, i) => i !== originalIdx));
  };

  const renameModel = (originalIdx: number, newName: string) => {
    setModalModels(prev =>
      prev.map((m, i) => i === originalIdx ? { ...m, name: newName } : m)
    );
  };

  const removeColumn = (modelIdx: number, col: string) => {
    setModalModels(prev =>
      prev.map((m, i) =>
        i === modelIdx ? { ...m, columns: m.columns.filter(c => c !== col) } : m
      )
    );
  };

  const addColumn = (modelIdx: number) => {
    const val = addColumnInputs[modelIdx]?.trim();
    if (!val) return;
    setModalModels(prev =>
      prev.map((m, i) => {
        if (i !== modelIdx) return m;
        if (m.columns.includes(val)) return m;
        return { ...m, columns: [...m.columns, val] };
      })
    );
    setAddColumnInputs(prev => prev.map((v, i) => i === modelIdx ? '' : v));
  };

  const setColumnInput = (modelIdx: number, val: string) => {
    setAddColumnInputs(prev => prev.map((v, i) => i === modelIdx ? val : v));
  };

  return (
    <>
      <div
        className="modal-backdrop"
        onClick={e => { if (e.target === e.currentTarget) onClose(); }}
      >
        <div className="modal-card">
          <div className="modal-header">
            <div className="modal-title"><h3>Manage Models &amp; Columns</h3></div>
            <button className="btn btn-icon modal-close" onClick={onClose}>&times;</button>
          </div>

          <div className="modal-body">
            {/* Toolbar */}
            {/* Search */}
            <div className="flex flex-row items-center gap-2">
              <SearchIcon size={16} className='text-gray-500' />
              <input
                type="text"
                className="py-2 px-4 w-full bg-transparent focus:outline-none"
                placeholder={`Search models…`}
                value={modelSearch}
                onChange={e => setModelSearch(e.target.value)}
              />

              <button className="flex flex-row items-center gap-2 btn btn-sm btn-primary text-white" onClick={() => setIsAddDialogOpen(true)}>
                <PlusIcon size={16} />
                Add
              </button>
            </div>

            {/* Model list */}
            <div className="model-list">
              {visibleModels.length === 0 && (
                <div style={{ color: 'var(--text-muted)', fontSize: '0.85rem', padding: '8px 0' }}>
                  No models match &ldquo;{modelSearch}&rdquo;
                </div>
              )}

              {visibleModels.map(entry => (
                <div key={entry.idx} className="model-item-card">
                  {/* Model name + remove */}
                  <div className="model-item-header">
                    <input
                      type="text"
                      className="form-control model-name-input"
                      value={entry.name}
                      onChange={e => renameModel(entry.idx, e.target.value)}
                    />
                    <button
                      className="btn btn-sm btn-danger-ghost"
                      onClick={() => removeModel(entry.idx)}
                    >
                      Remove
                    </button>
                  </div>

                  {/* Column tags */}
                  <div className="column-tags-wrapper">
                    {entry.columns.map((col, colIdx) => (
                      <span key={`${col}-${colIdx}`} className="column-tag">
                        {col}
                        <button onClick={() => removeColumn(entry.idx, col)}>&times;</button>
                      </span>
                    ))}
                  </div>

                  {/* Add column input */}
                  <div className="add-column-form">
                    <input
                      type="text"
                      className="form-control add-column-input"
                      placeholder="+ Add column..."
                      value={addColumnInputs[entry.idx] || ''}
                      onChange={e => setColumnInput(entry.idx, e.target.value)}
                      onKeyDown={e => { if (e.key === 'Enter') addColumn(entry.idx); }}
                    />
                    <button
                      className="btn btn-sm btn-secondary"
                      onClick={() => addColumn(entry.idx)}
                    >
                      Add
                    </button>
                  </div>
                </div>
              ))}
            </div>
          </div>

          <div className="modal-footer">
            <button className="btn btn-primary" onClick={handleSave}>
              Save Changes
            </button>
          </div>
        </div>
      </div>

      {/* Nested: Add Model Dialog */}
      {isAddDialogOpen && (
        <AddModelDialog
          onConfirm={handleConfirmAddModel}
          onClose={() => setIsAddDialogOpen(false)}
          showToast={showToast}
        />
      )}
    </>
  );
}

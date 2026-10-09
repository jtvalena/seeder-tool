'use client';

import { useState } from 'react';
import { Toast } from '../types';

interface AddModelDialogProps {
  onConfirm: (name: string, columns: string[]) => void;
  onClose: () => void;
  showToast: (msg: string, type: Toast['type']) => void;
}

const COLUMN_PRESETS = ['id', 'created_at', 'updated_at', 'amount', 'status', 'deleted_at'];

// ─────────────────────────────────────────────
// AddModelDialog — nested dialog for adding a new model
// ─────────────────────────────────────────────
export default function AddModelDialog({ onConfirm, onClose, showToast }: AddModelDialogProps) {
  const [name, setName]         = useState('');
  const [columnsText, setColumnsText] = useState('id, created_at, updated_at');

  const handleConfirm = () => {
    const trimmed = name.trim();
    if (!trimmed) { showToast('Model name cannot be empty', 'error'); return; }
    const cols = columnsText.split(',').map(c => c.trim()).filter(Boolean);
    onConfirm(trimmed, cols.length ? cols : ['id', 'created_at', 'updated_at']);
  };

  const appendPreset = (preset: string) => {
    setColumnsText(prev =>
      prev.trim() ? prev.replace(/,\s*$/, '') + `, ${preset}` : preset
    );
  };

  return (
    <div
      className="modal-backdrop modal-backdrop--nested"
      onClick={e => { if (e.target === e.currentTarget) onClose(); }}
    >
      <div className="modal-card modal-card--sm">
        <div className="modal-header">
          <div className="modal-title"><h3>Add New Model</h3></div>
          <button className="btn btn-icon modal-close" onClick={onClose}>&times;</button>
        </div>

        <div className="modal-body">
          <div className="form-group">
            <label className="form-label">Model Name <span className="required">*</span></label>
            <input
              className="form-control"
              placeholder="e.g. AdminCustomer"
              value={name}
              autoFocus
              onChange={e => setName(e.target.value)}
              onKeyDown={e => { if (e.key === 'Enter') handleConfirm(); }}
            />
          </div>

          <div className="form-group">
            <label className="form-label">
              Initial Columns
              <span className="form-hint">(comma-separated)</span>
            </label>
            <textarea
              className="form-control"
              rows={3}
              placeholder="id, created_at, updated_at, amount"
              value={columnsText}
              onChange={e => setColumnsText(e.target.value)}
            />
            <div className="field-presets" style={{ marginTop: 6 }}>
              {COLUMN_PRESETS.map(preset => (
                <span key={preset} className="preset-chip" onClick={() => appendPreset(preset)}>
                  + {preset}
                </span>
              ))}
            </div>
          </div>
        </div>

        <div className="modal-footer">
          <button className="btn btn-secondary" onClick={onClose}>Cancel</button>
          <button className="btn btn-primary" onClick={handleConfirm}>Add Model</button>
        </div>
      </div>
    </div>
  );
}

import { useRef } from 'react';
import { Pin, Copy, Undo2, Trash2 } from 'lucide-react';
import { FIXED_HEADER } from './types';

interface NotepadPanelProps {
  notepad: string;
  setNotepad: (v: string) => void;
  notepadRef: React.RefObject<HTMLTextAreaElement | null>;
  historyStack: string[];
  // Actions
  onCopy: () => void;
  onDownload: () => void;
  onUndo: () => void;
  onClear: () => void;
  // CSV import
  csvImportRef: React.RefObject<HTMLInputElement | null>;
  onCsvImport: (e: React.ChangeEvent<HTMLInputElement>) => void;
}

// ─────────────────────────────────────────────
// NotepadPanel — right panel: locked header, textarea, action buttons
// ─────────────────────────────────────────────
export default function NotepadPanel({
  notepad, setNotepad, notepadRef,
  historyStack,
  onCopy, onDownload, onUndo, onClear,
  csvImportRef, onCsvImport,
}: NotepadPanelProps) {
  return (
    <section className="panel notepad-panel">
      <div className="panel-header">
        <div className="panel-title">
          <h2>Generated Seeders Notepad</h2>
          {/* <span className="counter-badge">
            {entryCount} {entryCount === 1 ? 'entry' : 'entries'}
          </span> */}
        </div>
        <div className="panel-actions">
          {/* <button className="btn btn-sm btn-secondary" onClick={onDownload}>Download</button> */}
          <button className="btn btn-sm btn-danger-ghost" onClick={onClear}>
            <Trash2 size={16} /> Clear
          </button>
          <button className="btn btn-sm btn-secondary" onClick={onUndo} disabled={historyStack.length === 0}>
            <Undo2 size={16} /> Undo
          </button>
          <button className="btn btn-sm btn-primary" onClick={onCopy}>
            <Copy size={16} /> Copy
          </button>
        </div>
      </div>

      {/* Locked header */}
      <div className="notepad-fixed-header">
        <span className="fixed-header-text flex items-center gap-2">
          <Pin className='text-yellow-500' size={16} />{FIXED_HEADER}
        </span>
      </div>

      <div className="notepad-wrapper">
        <textarea
          ref={notepadRef}
          className="notepad-textarea"
          placeholder={`Generated seeder outputs will appear here...`}
          spellCheck={false}
          value={notepad}
          onChange={e => setNotepad(e.target.value)}
        />
      </div>
    </section>
  );
}

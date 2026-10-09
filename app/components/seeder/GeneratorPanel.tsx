import SearchableSelect from '../SearchableSelect';
import SeederFields from './SeederFields';
import { SeederType, Fields, SEEDER_TYPES } from './types';

interface GeneratorPanelProps {
  // Company
  company: string;
  setCompany: (c: string) => void;
  customCompany: string;
  setCustomCompany: (v: string) => void;
  // Seeder type
  seederType: SeederType;
  onSeederTypeChange: (t: SeederType) => void;
  // Fields
  fields: Fields;
  setField: <K extends keyof Fields>(key: K, val: Fields[K]) => void;
  handleModelChange: (val: string) => void;
  // Model data
  modelNames: string[];
  columnOptions: string[];
  modelCount: number;
  // Actions
  onGenerate: () => void;
  onReset: () => void;
}

// ─────────────────────────────────────────────
// GeneratorPanel — left panel: company, seeder type, dynamic fields, generate button
// ─────────────────────────────────────────────
export default function GeneratorPanel({
  company, setCompany, customCompany, setCustomCompany,
  seederType, onSeederTypeChange,
  fields, setField, handleModelChange,
  modelNames, columnOptions, modelCount,
  onGenerate, onReset,
}: GeneratorPanelProps) {
  return (
    <section className="panel generator-panel">
      <div className="panel-header">
        <div className="panel-title"><h2>Seeder Generator</h2></div>
        <button className="btn btn-sm btn-ghost" onClick={onReset}>Reset</button>
      </div>

      <form
        className="generator-form"
        onSubmit={e => { e.preventDefault(); onGenerate(); }}
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
            {/* <button
              type="button"
              className={`company-btn company-btn-custom ${company === 'CUSTOM' ? 'active' : ''}`}
              onClick={() => setCompany('CUSTOM')}
            >
              Custom
            </button> */}
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
            noFilter
            onChange={(val, isSelection) => {
              if (isSelection || SEEDER_TYPES.includes(val as SeederType)) {
                onSeederTypeChange(val as SeederType);
              }
            }}
          />
        </div>

        <div className="divider" />

        {/* Dynamic fields for the selected seeder type */}
        <div className="dynamic-fields">
          <SeederFields
            seederType={seederType}
            fields={fields}
            setField={setField}
            modelNames={modelNames}
            columnOptions={columnOptions}
            modelCount={modelCount}
            handleModelChange={handleModelChange}
          />
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
  );
}

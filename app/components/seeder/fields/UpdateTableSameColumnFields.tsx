import SearchableSelect from '../../SearchableSelect';
import { ModelFieldProps } from '../types';

export default function UpdateTableSameColumnFields({
  fields, setField, modelNames, columnOptions, modelCount, handleModelChange,
}: ModelFieldProps) {
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
        <input
          className="form-control"
          placeholder="e.g. 101, 102, 103"
          value={fields.ids}
          onChange={e => setField('ids', e.target.value)}
        />
      </div>

      <div className="form-group">
        <label className="form-label">new value <span className="required">*</span></label>
        <input
          className="form-control"
          placeholder="e.g. 2"
          value={fields.newValue}
          onChange={e => setField('newValue', e.target.value)}
        />
        <div className="field-presets">
          {['NULL', '1', '0', '2'].map(v => (
            <span key={v} className="preset-chip" onClick={() => setField('newValue', v)}>{v}</span>
          ))}
        </div>
      </div>
    </>
  );
}

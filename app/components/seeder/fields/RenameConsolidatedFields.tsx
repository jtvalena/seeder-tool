import { BaseFieldProps } from '../types';

export default function RenameConsolidatedFields({ fields, setField }: BaseFieldProps) {
  return (
    <>
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
        <label className="form-label">proceed (yes/no)</label>
        <select
          className="form-control"
          value={fields.proceed}
          onChange={e => setField('proceed', e.target.value)}
        >
          <option value="yes">Yes</option>
          <option value="no">No</option>
        </select>
      </div>
    </>
  );
}

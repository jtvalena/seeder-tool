import { BaseFieldProps } from '../types';

export default function RevertFasBasketPoFields({ fields, setField }: BaseFieldProps) {
  return (
    <>
      <div className="form-group">
        <label className="form-label">PB# <span className="required">*</span></label>
        <input
          className="form-control"
          placeholder="e.g. 006565"
          value={fields.pbNumber}
          onChange={e => setField('pbNumber', e.target.value)}
        />
      </div>

      <div className="form-group">
        <label className="form-label">Proceed</label>
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

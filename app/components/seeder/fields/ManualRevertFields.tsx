import { BaseFieldProps } from '../types';

export default function ManualRevertFields({ fields, setField }: BaseFieldProps) {
  return (
    <div className="form-group">
      <label className="form-label">ids <span className="required">*</span></label>
      <input
        className="form-control"
        placeholder="e.g. 5962"
        value={fields.ids}
        onChange={e => setField('ids', e.target.value)}
      />
    </div>
  );
}

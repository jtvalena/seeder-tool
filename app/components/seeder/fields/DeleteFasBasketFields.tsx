import { BaseFieldProps } from '../types';

export default function DeleteFasBasketFields({ fields, setField }: BaseFieldProps) {
  return (
    <div className="form-group">
      <label className="form-label">admin_fas_basket_id <span className="required">*</span></label>
      <input
        className="form-control"
        placeholder="e.g. 5962"
        value={fields.fasBasketId}
        onChange={e => setField('fasBasketId', e.target.value)}
      />
    </div>
  );
}

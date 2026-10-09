import { BaseFieldProps } from '../types';

export default function PoRecomputeFields({ fields, setField }: BaseFieldProps) {
  return (
    <>
      <div className="form-group">
        <label className="form-label">Po Ids <span className="required">*</span></label>
        <input
          className="form-control"
          placeholder="e.g. 8299"
          value={fields.poIds}
          onChange={e => setField('poIds', e.target.value)}
        />
      </div>

      <div className="form-group">
        <label className="form-label">What do you want to recompute?</label>
        <select
          className="form-control"
          value={fields.recomputeOption}
          onChange={e => setField('recomputeOption', e.target.value)}
        >
          <option value="1">1 - Recompute Total</option>
          <option value="2">2 - Recompute Amount to Pay</option>
          <option value="3">3 - Both</option>
        </select>
      </div>

      <div className="form-group">
        <label className="form-label">What quantity will be the basis for recompute?</label>
        <select
          className="form-control"
          value={fields.quantityBasis}
          onChange={e => setField('quantityBasis', e.target.value)}
        >
          <option value="2">Received Quantity</option>
          <option value="1">Quantity</option>
        </select>
      </div>

      <div className="form-group">
        <label className="form-label">Proceed with recompute?</label>
        <select
          className="form-control"
          value={fields.proceed}
          onChange={e => setField('proceed', e.target.value)}
        >
          <option value="y">Yes</option>
          <option value="n">No</option>
        </select>
      </div>
    </>
  );
}

import { BaseFieldProps } from '../types';

export default function UpdatePoPriceFields({ fields, setField }: BaseFieldProps) {
  return (
    <>
      <div className="form-group">
        <label className="form-label">po id <span className="required">*</span></label>
        <input
          className="form-control"
          placeholder="e.g. 6419"
          value={fields.poId}
          onChange={e => setField('poId', e.target.value)}
        />
      </div>

      <div className="form-group">
        <label className="form-label">po item id <span className="required">*</span></label>
        <input
          className="form-control"
          placeholder="e.g. 1024"
          value={fields.poItemId}
          onChange={e => setField('poItemId', e.target.value)}
        />
      </div>

      <div className="form-group">
        <label className="form-label">new dr price <span className="required">*</span></label>
        <input
          className="form-control"
          placeholder="e.g. 150.00"
          value={fields.newDrPrice}
          onChange={e => setField('newDrPrice', e.target.value)}
        />
      </div>

      <div className="form-group">
        <label className="form-label">bases of quantity [0/1]</label>
        <select
          className="form-control"
          value={fields.basesOfQuantity}
          onChange={e => setField('basesOfQuantity', e.target.value)}
        >
          <option value="1">1 - Received Quantity</option>
          <option value="0">0 - Quantity</option>
        </select>
      </div>

      <div className="form-group">
        <label className="form-label">want to recompute item amount to pay?</label>
        <select
          className="form-control"
          value={fields.wantToRecompute}
          onChange={e => setField('wantToRecompute', e.target.value)}
        >
          <option value="y">Yes</option>
          <option value="n">No</option>
        </select>
      </div>

      <div className="form-group">
        <label className="form-label">proceed?</label>
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

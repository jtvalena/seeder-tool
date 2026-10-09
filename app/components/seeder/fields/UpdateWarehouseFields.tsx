import { BaseFieldProps } from '../types';

export default function UpdateWarehouseFields({ fields, setField }: BaseFieldProps) {
  return (
    <>
      <div className="form-group">
        <label className="form-label">po_id <span className="required">*</span></label>
        <input
          className="form-control"
          placeholder="e.g. 8299"
          value={fields.poId}
          onChange={e => setField('poId', e.target.value)}
        />
      </div>

      <div className="form-group">
        <label className="form-label">warehouse_id <span className="required">*</span></label>
        <input
          className="form-control"
          placeholder="e.g. 12"
          value={fields.warehouseId}
          onChange={e => setField('warehouseId', e.target.value)}
        />
      </div>
    </>
  );
}

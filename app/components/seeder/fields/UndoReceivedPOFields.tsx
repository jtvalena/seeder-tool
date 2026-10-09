import { BaseFieldProps } from '../types';

export default function UndoReceivedPOFields({ fields, setField }: BaseFieldProps) {
  return (
    <>
      <div className="form-group">
        <label className="form-label">undo PO received</label>
        <input
          className="form-control"
          placeholder="e.g. 1"
          value={fields.undoPoReceived}
          onChange={e => setField('undoPoReceived', e.target.value)}
        />
      </div>

      <div className="form-group">
        <label className="form-label">PO id <span className="required">*</span></label>
        <input
          className="form-control"
          placeholder="e.g. 10960"
          value={fields.poId}
          onChange={e => setField('poId', e.target.value)}
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

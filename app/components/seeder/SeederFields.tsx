import { SeederType, BaseFieldProps, ModelFieldProps } from './types';

import UpdateTableFields          from './fields/UpdateTableFields';
import UpdatePoPriceFields        from './fields/UpdatePoPriceFields';
import UpdateWarehouseFields      from './fields/UpdateWarehouseFields';
import UpdateTableSameColumnFields from './fields/UpdateTableSameColumnFields';
import DeleteFasBasketFields      from './fields/DeleteFasBasketFields';
import ManualRevertFields         from './fields/ManualRevertFields';
import PoRecomputeFields          from './fields/PoRecomputeFields';
import RenameConsolidatedFields   from './fields/RenameConsolidatedFields';
import RevertFasBasketPoFields    from './fields/RevertFasBasketPoFields';
import UndoReceivedPOFields       from './fields/UndoReceivedPOFields';

interface SeederFieldsProps extends ModelFieldProps {
  seederType: SeederType;
}

// ─────────────────────────────────────────────
// Maps the active SeederType to its field component.
// Adding a new seeder type = add one case here + one file in fields/.
// ─────────────────────────────────────────────
export default function SeederFields({
  seederType,
  fields,
  setField,
  modelNames,
  columnOptions,
  modelCount,
  handleModelChange,
}: SeederFieldsProps) {
  const base: BaseFieldProps     = { fields, setField };
  const model: ModelFieldProps   = { ...base, modelNames, columnOptions, modelCount, handleModelChange };

  switch (seederType) {
    case 'UpdateTable':
      return <UpdateTableFields {...model} />;
    case 'UpdatePoPrice':
      return <UpdatePoPriceFields {...base} />;
    case 'UpdateWarehouseOfReceivedPO':
      return <UpdateWarehouseFields {...base} />;
    case 'UpdateTableSameColumnMultipleRecords':
      return <UpdateTableSameColumnFields {...model} />;
    case 'DeleteFasBasketAndRequisitionConnection':
      return <DeleteFasBasketFields {...base} />;
    case 'ManualRevertOfPurchaseBasket':
      return <ManualRevertFields {...base} />;
    case 'PoRecomputeTotalOrAmountToPay':
      return <PoRecomputeFields {...base} />;
    case 'RenameConsolidatedAdminItems':
      return <RenameConsolidatedFields {...base} />;
    case 'RevertFasBasketPoCreationSeeder':
      return <RevertFasBasketPoFields {...base} />;
    case 'UndoReceivedPOSeeder':
      return <UndoReceivedPOFields {...base} />;
    default:
      return null;
  }
}

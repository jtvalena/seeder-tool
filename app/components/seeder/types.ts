// ─────────────────────────────────────────────
// Seeder Tool — Shared Types & Constants
// ─────────────────────────────────────────────

export type SeederType =
  | 'UpdateTable'
  | 'UpdatePoPrice'
  | 'UpdateWarehouseOfReceivedPO'
  | 'UpdateTableSameColumnMultipleRecords'
  | 'DeleteFasBasketAndRequisitionConnection'
  | 'ManualRevertOfPurchaseBasket'
  | 'PoRecomputeTotalOrAmountToPay'
  | 'RenameConsolidatedAdminItems'
  | 'RevertFasBasketPoCreationSeeder'
  | 'UndoReceivedPOSeeder';

export const SEEDER_TYPES: SeederType[] = [
  'UpdateTable',
  'UpdatePoPrice',
  'UpdateWarehouseOfReceivedPO',
  'UpdateTableSameColumnMultipleRecords',
  'DeleteFasBasketAndRequisitionConnection',
  'ManualRevertOfPurchaseBasket',
  'PoRecomputeTotalOrAmountToPay',
  'RenameConsolidatedAdminItems',
  'RevertFasBasketPoCreationSeeder',
  'UndoReceivedPOSeeder',
];

export interface Toast {
  id: number;
  message: string;
  type: 'success' | 'info' | 'error';
}

export interface Fields {
  model: string;
  column: string;
  id: string;
  newValue: string;
  poId: string;
  poItemId: string;
  newDrPrice: string;
  basesOfQuantity: string;
  wantToRecompute: string;
  proceed: string;
  warehouseId: string;
  ids: string;
  fasBasketId: string;
  poIds: string;
  recomputeOption: string;
  quantityBasis: string;
  pbNumber: string;
  undoPoReceived: string;
}

export const DEFAULT_FIELDS: Fields = {
  model: '',
  column: '',
  id: '',
  newValue: '',
  poId: '',
  poItemId: '',
  newDrPrice: '',
  basesOfQuantity: '1',
  wantToRecompute: 'y',
  proceed: 'y',
  warehouseId: '',
  ids: '',
  fasBasketId: '',
  poIds: '',
  recomputeOption: '2',
  quantityBasis: '1',
  pbNumber: '',
  undoPoReceived: '1',
};

export const FIXED_HEADER = 'Seeder to run: php artisan db:seed BulkUpdateFromText';

// ── Shared field-component prop shapes ───────────────────────

export interface BaseFieldProps {
  fields: Fields;
  setField: <K extends keyof Fields>(key: K, val: Fields[K]) => void;
}

export interface ModelFieldProps extends BaseFieldProps {
  modelNames: string[];
  columnOptions: string[];
  modelCount: number;
  handleModelChange: (val: string) => void;
}

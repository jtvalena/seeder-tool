// Load extracted 93 Models and Columns reference dictionary
const DEFAULT_MODELS_DATA = (typeof MODELS_REFERENCE !== 'undefined') ? MODELS_REFERENCE : {
  "AdminWarehouseItemStock": ["id", "admin_warehouse_id", "admin_item_id", "check_in", "check_out", "quantity", "accounted", "unaccounted", "tagged", "msq", "reserved", "deleted_at", "created_at", "updated_at"],
  "AdminPurchaseOrder": ["id", "po_num", "location_id", "shipping_via_id", "vendor_id", "total_amount", "remarks", "status", "type", "payment_method", "amount_to_pay", "deleted_at", "created_at", "updated_at"],
  "AdminPurchaseBasket": ["id", "status", "po_id", "total_price", "quantity", "deleted_at", "created_at", "updated_at"],
  "AdminFasBasket": ["id", "requisition_id", "status", "deleted_at", "created_at", "updated_at"],
  "AdminWarehouse": ["id", "name", "code", "status", "deleted_at", "created_at", "updated_at"],
  "AdminItems": ["id", "dr_price", "price", "code", "name", "status", "deleted_at", "created_at", "updated_at"]
};

const SEEDER_TYPES = [
  "UpdateTable",
  "UpdatePoPrice",
  "UpdateWarehouseOfReceivedPO",
  "UpdateTableSameColumnMultipleRecords",
  "DeleteFasBasketAndRequisitionConnection",
  "ManualRevertOfPurchaseBasket",
  "PoRecomputeTotalOrAmountToPay"
];

// Application State
let modelsData = {};
let historyStack = [];

// Component Instances
let seederTypeSearchable = null;
let modelSearchable = null;
let columnSearchable = null;

// DOM Elements
const companySelect = document.getElementById('companySelect');
const customCompanyInput = document.getElementById('customCompanyInput');
const dynamicFieldsContainer = document.getElementById('dynamicFieldsContainer');
const generateBtn = document.getElementById('generateBtn');
const resetFormBtn = document.getElementById('resetFormBtn');
const notepadTextarea = document.getElementById('notepadTextarea');
const copyAllBtn = document.getElementById('copyAllBtn');
const downloadBtn = document.getElementById('downloadBtn');
const undoBtn = document.getElementById('undoBtn');
const clearNotepadBtn = document.getElementById('clearNotepadBtn');
const entryCounter = document.getElementById('entryCounter');
const themeToggleBtn = document.getElementById('themeToggleBtn');
const manageModelsBtn = document.getElementById('manageModelsBtn');

// Modal Elements
const modelsModal = document.getElementById('modelsModal');
const closeModalBtn = document.getElementById('closeModalBtn');
const modelList = document.getElementById('modelList');
const addNewModelBtn = document.getElementById('addNewModelBtn');
const resetModelsBtn = document.getElementById('resetModelsBtn');
const saveModelsBtn = document.getElementById('saveModelsBtn');
const toastContainer = document.getElementById('toastContainer');

// Searchable Select Component Class
class SearchableSelect {
  constructor({ containerId, options = [], value = '', placeholder = 'Search...', onChange, allowCustom = true }) {
    this.container = document.getElementById(containerId);
    this.options = options;
    this.value = value;
    this.placeholder = placeholder;
    this.onChange = onChange;
    this.allowCustom = allowCustom;
    this.highlightedIndex = -1;
    this.isOpen = false;

    this.render();
    this.attachEvents();
  }

  render() {
    if (!this.container) return;
    this.container.innerHTML = `
      <div class="searchable-input-container">
        <input type="text" class="form-control searchable-input" placeholder="${this.placeholder}" value="${this.value}" autocomplete="off" spellcheck="false">
        <span class="searchable-arrow">▼</span>
      </div>
      <div class="searchable-dropdown hidden"></div>
    `;

    this.input = this.container.querySelector('.searchable-input');
    this.dropdown = this.container.querySelector('.searchable-dropdown');
  }

  attachEvents() {
    if (!this.input) return;

    this.input.addEventListener('focus', () => {
      this.open();
    });

    this.input.addEventListener('click', () => {
      if (!this.isOpen) this.open();
    });

    this.input.addEventListener('input', () => {
      this.value = this.input.value;
      if (!this.isOpen) this.open();
      this.renderDropdown(this.input.value);
      if (this.onChange) this.onChange(this.value, false);
    });

    this.input.addEventListener('keydown', (e) => {
      const items = this.dropdown.querySelectorAll('.searchable-option');
      if (e.key === 'ArrowDown') {
        e.preventDefault();
        if (!this.isOpen) this.open();
        this.highlightedIndex = Math.min(this.highlightedIndex + 1, items.length - 1);
        this.updateHighlight(items);
      } else if (e.key === 'ArrowUp') {
        e.preventDefault();
        if (!this.isOpen) this.open();
        this.highlightedIndex = Math.max(this.highlightedIndex - 1, 0);
        this.updateHighlight(items);
      } else if (e.key === 'Enter') {
        if (this.isOpen && this.highlightedIndex >= 0 && items[this.highlightedIndex]) {
          e.preventDefault();
          items[this.highlightedIndex].click();
        }
      } else if (e.key === 'Escape') {
        this.close();
      }
    });

    document.addEventListener('click', (e) => {
      if (this.container && !this.container.contains(e.target)) {
        this.close();
      }
    });
  }

  open() {
    this.isOpen = true;
    this.container.classList.add('open');
    this.dropdown.classList.remove('hidden');
    this.renderDropdown(this.input.value);
  }

  close() {
    this.isOpen = false;
    this.container.classList.remove('open');
    this.dropdown.classList.add('hidden');
    this.highlightedIndex = -1;
  }

  renderDropdown(filterText = '') {
    const query = filterText.trim().toLowerCase();
    const filtered = this.options.filter(opt => opt.toLowerCase().includes(query));

    this.dropdown.innerHTML = '';
    this.highlightedIndex = -1;

    if (filtered.length === 0) {
      const noRes = document.createElement('div');
      noRes.className = 'searchable-no-results';
      noRes.textContent = this.allowCustom ? (filterText ? `Use custom value: "${filterText}"` : 'Type or select an option...') : 'No matching options';
      this.dropdown.appendChild(noRes);
      return;
    }

    filtered.forEach((opt) => {
      const div = document.createElement('div');
      div.className = `searchable-option ${opt === this.value ? 'selected' : ''}`;
      div.textContent = opt;

      div.addEventListener('click', () => {
        this.value = opt;
        this.input.value = opt;
        this.close();
        if (this.onChange) this.onChange(this.value, true);
      });

      this.dropdown.appendChild(div);
    });
  }

  updateHighlight(items) {
    items.forEach((item, idx) => {
      if (idx === this.highlightedIndex) {
        item.classList.add('highlighted');
        item.scrollIntoView({ block: 'nearest' });
      } else {
        item.classList.remove('highlighted');
      }
    });
  }

  setOptions(newOptions, selectedValue = '') {
    this.options = newOptions;
    this.setValue(selectedValue);
    if (this.input) {
      this.input.placeholder = newOptions.length > 0 ? 'Search column...' : 'Select a model first...';
    }
  }

  setValue(val) {
    this.value = val;
    if (this.input) this.input.value = val;
  }

  getValue() {
    return this.input ? this.input.value.trim() : this.value;
  }
}

// Initialization
document.addEventListener('DOMContentLoaded', () => {
  loadModelsData();
  loadNotepadFromStorage();
  initSeederTypeSearchable();
  setupEventListeners();
  renderDynamicFields();
  updateEntryCounter();
});

// Load Models & Columns Dictionary from localStorage or Reference
function loadModelsData() {
  const stored = localStorage.getItem('seeder_models_dictionary');
  if (stored) {
    try {
      modelsData = JSON.parse(stored);
    } catch (e) {
      modelsData = JSON.parse(JSON.stringify(DEFAULT_MODELS_DATA));
    }
  } else {
    modelsData = JSON.parse(JSON.stringify(DEFAULT_MODELS_DATA));
  }
}

function saveModelsDataToStorage() {
  localStorage.setItem('seeder_models_dictionary', JSON.stringify(modelsData));
}

function loadNotepadFromStorage() {
  const savedText = localStorage.getItem('seeder_notepad_text');
  if (savedText) {
    notepadTextarea.value = savedText;
  }
}

function saveNotepadToStorage() {
  localStorage.setItem('seeder_notepad_text', notepadTextarea.value);
  updateEntryCounter();
}

// Initialize Searchable Seeder Type
function initSeederTypeSearchable() {
  seederTypeSearchable = new SearchableSelect({
    containerId: 'seederTypeSearchable',
    options: SEEDER_TYPES,
    value: '',
    placeholder: 'Search seeder type...',
    allowCustom: false,
    onChange: (val, isSelection) => {
      if (isSelection || SEEDER_TYPES.includes(val)) {
        renderDynamicFields();
      }
    }
  });
}

// Event Listeners Setup
function setupEventListeners() {
  themeToggleBtn.addEventListener('click', () => {
    document.body.classList.toggle('light-mode');
    document.body.classList.toggle('dark-mode');
    const theme = document.body.classList.contains('light-mode') ? 'light' : 'dark';
    localStorage.setItem('seeder_theme', theme);
  });

  const savedTheme = localStorage.getItem('seeder_theme');
  if (savedTheme === 'light') {
    document.body.classList.remove('dark-mode');
    document.body.classList.add('light-mode');
  }

  companySelect.addEventListener('change', () => {
    if (companySelect.value === 'CUSTOM') {
      customCompanyInput.classList.remove('hidden');
      customCompanyInput.focus();
    } else {
      customCompanyInput.classList.add('hidden');
    }
  });

  generateBtn.addEventListener('click', generateSeederRun);

  document.addEventListener('keydown', (e) => {
    if ((e.ctrlKey || e.metaKey) && e.key === 'Enter') {
      e.preventDefault();
      generateSeederRun();
    }
  });

  resetFormBtn.addEventListener('click', () => {
    document.getElementById('seederForm').reset();
    customCompanyInput.classList.add('hidden');
    companySelect.value = '';
    renderDynamicFields();
    showToast('Form reset to default values', 'info');
  });

  notepadTextarea.addEventListener('input', saveNotepadToStorage);

  copyAllBtn.addEventListener('click', () => {
    if (!notepadTextarea.value.trim()) {
      showToast('Notepad is empty!', 'error');
      return;
    }
    navigator.clipboard.writeText(notepadTextarea.value).then(() => {
      showToast('Copied notepad content to clipboard!', 'success');
    }).catch(err => {
      notepadTextarea.select();
      document.execCommand('copy');
      showToast('Copied notepad content to clipboard!', 'success');
    });
  });

  downloadBtn.addEventListener('click', () => {
    const text = notepadTextarea.value;
    if (!text.trim()) {
      showToast('Notepad is empty!', 'error');
      return;
    }
    const blob = new Blob([text], { type: 'text/plain;charset=utf-8' });
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = `seeder_runs_${new Date().toISOString().slice(0, 10)}.txt`;
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
    URL.revokeObjectURL(url);
    showToast('Downloaded notepad as .txt file', 'success');
  });

  undoBtn.addEventListener('click', () => {
    if (historyStack.length > 0) {
      const previousState = historyStack.pop();
      notepadTextarea.value = previousState;
      saveNotepadToStorage();
      if (historyStack.length === 0) {
        undoBtn.disabled = true;
      }
      showToast('Undid last generated entry', 'info');
    }
  });

  clearNotepadBtn.addEventListener('click', () => {
    if (!notepadTextarea.value.trim()) return;
    if (confirm('Are you sure you want to clear the notepad?')) {
      historyStack.push(notepadTextarea.value);
      undoBtn.disabled = false;
      notepadTextarea.value = '';
      saveNotepadToStorage();
      showToast('Notepad cleared', 'info');
    }
  });

  manageModelsBtn.addEventListener('click', openModelsModal);
  closeModalBtn.addEventListener('click', closeModelsModal);
  modelsModal.addEventListener('click', (e) => {
    if (e.target === modelsModal) closeModelsModal();
  });
  addNewModelBtn.addEventListener('click', handleAddNewModel);
  resetModelsBtn.addEventListener('click', handleResetModels);
  saveModelsBtn.addEventListener('click', handleSaveModelsModal);
}

// Render Dynamic Fields based on Seeder Type
function renderDynamicFields() {
  const seederType = seederTypeSearchable ? seederTypeSearchable.getValue() : 'UpdateTable';
  dynamicFieldsContainer.innerHTML = '';

  switch (seederType) {
    case 'UpdateTable':
      renderUpdateTableFields();
      break;
    case 'UpdatePoPrice':
      renderUpdatePoPriceFields();
      break;
    case 'UpdateWarehouseOfReceivedPO':
      renderUpdateWarehouseOfReceivedPOFields();
      break;
    case 'UpdateTableSameColumnMultipleRecords':
      renderUpdateTableSameColumnMultipleRecordsFields();
      break;
    case 'DeleteFasBasketAndRequisitionConnection':
      renderDeleteFasBasketAndRequisitionConnectionFields();
      break;
    case 'ManualRevertOfPurchaseBasket':
      renderManualRevertOfPurchaseBasketFields();
      break;
    case 'PoRecomputeTotalOrAmountToPay':
      renderPoRecomputeTotalOrAmountToPayFields();
      break;
    default:
      renderUpdateTableFields();
      break;
  }
}

// Field 1: UpdateTable
function renderUpdateTableFields() {
  const html = `
    <div class="form-group">
      <label class="form-label">model <span class="required">*</span></label>
      <div id="modelSearchable" class="searchable-select-wrapper"></div>
    </div>

    <div class="form-group">
      <label class="form-label">column <span class="required">*</span></label>
      <div id="columnSearchable" class="searchable-select-wrapper"></div>
    </div>

    <div class="form-group">
      <label class="form-label" for="idInput">id <span class="required">*</span></label>
      <input type="text" id="idInput" class="form-control" placeholder="e.g. 463">
    </div>

    <div class="form-group">
      <label class="form-label" for="newValueInput">new value <span class="required">*</span></label>
      <input type="text" id="newValueInput" class="form-control" placeholder="e.g. NULL or 2">
      <div class="field-presets">
        <span class="preset-chip" onclick="setPresetValue('newValueInput', 'NULL')">NULL</span>
        <span class="preset-chip" onclick="setPresetValue('newValueInput', '1')">1</span>
        <span class="preset-chip" onclick="setPresetValue('newValueInput', '0')">0</span>
        <span class="preset-chip" onclick="setPresetValue('newValueInput', '2')">2</span>
        <span class="preset-chip" onclick="setPresetValue('newValueInput', 'NOW()')">NOW()</span>
      </div>
    </div>
  `;
  dynamicFieldsContainer.innerHTML = html;

  initModelAndColumnSearchables('', '');
}

// Field 2: UpdatePoPrice
function renderUpdatePoPriceFields() {
  const html = `
    <div class="form-group">
      <label class="form-label" for="poIdInput">po id <span class="required">*</span></label>
      <input type="text" id="poIdInput" class="form-control" placeholder="e.g. 6419">
    </div>
    <div class="form-group">
      <label class="form-label" for="poItemIdInput">po item id <span class="required">*</span></label>
      <input type="text" id="poItemIdInput" class="form-control" placeholder="e.g. 1024">
    </div>
    <div class="form-group">
      <label class="form-label" for="newDrPriceInput">new dr price <span class="required">*</span></label>
      <input type="text" id="newDrPriceInput" class="form-control" placeholder="e.g. 150.00">
    </div>
    <div class="form-group">
      <label class="form-label" for="basesOfQuantitySelect">bases of quantity [0/1]</label>
      <select id="basesOfQuantitySelect" class="form-control">
        <option value="1" selected>1</option>
        <option value="0">0</option>
      </select>
    </div>
    <div class="form-group">
      <label class="form-label" for="wantToRecomputeSelect">want to recompute item amount to pay?</label>
      <select id="wantToRecomputeSelect" class="form-control">
        <option value="y" selected>y</option>
        <option value="n">n</option>
      </select>
    </div>
    <div class="form-group">
      <label class="form-label" for="proceedSelect">proceed?</label>
      <select id="proceedSelect" class="form-control">
        <option value="y" selected>y</option>
        <option value="n">n</option>
      </select>
    </div>
  `;
  dynamicFieldsContainer.innerHTML = html;
}

// Field 3: UpdateWarehouseOfReceivedPO
function renderUpdateWarehouseOfReceivedPOFields() {
  const html = `
    <div class="form-group">
      <label class="form-label" for="poIdInput">po_id <span class="required">*</span></label>
      <input type="text" id="poIdInput" class="form-control" placeholder="e.g. 8299">
    </div>
    <div class="form-group">
      <label class="form-label" for="warehouseIdInput">warehouse_id <span class="required">*</span></label>
      <input type="text" id="warehouseIdInput" class="form-control" placeholder="e.g. 12">
    </div>
  `;
  dynamicFieldsContainer.innerHTML = html;
}

// Field 4: UpdateTableSameColumnMultipleRecords
function renderUpdateTableSameColumnMultipleRecordsFields() {
  const html = `
    <div class="form-group">
      <label class="form-label">model <span class="required">*</span></label>
      <div id="modelSearchable" class="searchable-select-wrapper"></div>
    </div>

    <div class="form-group">
      <label class="form-label">column <span class="required">*</span></label>
      <div id="columnSearchable" class="searchable-select-wrapper"></div>
    </div>

    <div class="form-group">
      <label class="form-label" for="idsInput">ids <span class="required">*</span></label>
      <input type="text" id="idsInput" class="form-control" placeholder="e.g. 101, 102, 103">
    </div>

    <div class="form-group">
      <label class="form-label" for="newValueInput">new value <span class="required">*</span></label>
      <input type="text" id="newValueInput" class="form-control" placeholder="e.g. 2">
      <div class="field-presets">
        <span class="preset-chip" onclick="setPresetValue('newValueInput', 'NULL')">NULL</span>
        <span class="preset-chip" onclick="setPresetValue('newValueInput', '1')">1</span>
        <span class="preset-chip" onclick="setPresetValue('newValueInput', '0')">0</span>
        <span class="preset-chip" onclick="setPresetValue('newValueInput', '2')">2</span>
      </div>
    </div>
  `;
  dynamicFieldsContainer.innerHTML = html;

  initModelAndColumnSearchables('', '');
}

// Field 5: DeleteFasBasketAndRequisitionConnection
function renderDeleteFasBasketAndRequisitionConnectionFields() {
  const html = `
    <div class="form-group">
      <label class="form-label" for="fasBasketIdInput">admin_fas_basket_id <span class="required">*</span></label>
      <input type="text" id="fasBasketIdInput" class="form-control" placeholder="e.g. 5962">
    </div>
  `;
  dynamicFieldsContainer.innerHTML = html;
}

// Field 6: ManualRevertOfPurchaseBasket
function renderManualRevertOfPurchaseBasketFields() {
  const html = `
    <div class="form-group">
      <label class="form-label" for="idsInput">ids <span class="required">*</span></label>
      <input type="text" id="idsInput" class="form-control" placeholder="e.g. 5962">
    </div>
  `;
  dynamicFieldsContainer.innerHTML = html;
}

// Field 7: PoRecomputeTotalOrAmountToPay
function renderPoRecomputeTotalOrAmountToPayFields() {
  const html = `
    <div class="form-group">
      <label class="form-label" for="poIdsInput">Po Ids <span class="required">*</span></label>
      <input type="text" id="poIdsInput" class="form-control" placeholder="e.g. 8299">
    </div>

    <div class="form-group">
      <label class="form-label" for="recomputeOptionSelect">What do you want to recompute?</label>
      <select id="recomputeOptionSelect" class="form-control">
        <option value="1">1 - Recompute Total</option>
        <option value="2" selected>2 - Recompute Amount to Pay</option>
        <option value="3">3 - Both</option>
      </select>
    </div>

    <div class="form-group">
      <label class="form-label" for="quantityBasisSelect">What quantity will be the basis for recompute?</label>
      <select id="quantityBasisSelect" class="form-control">
        <option value="1" selected>1</option>
        <option value="0">0</option>
      </select>
    </div>

    <div class="form-group">
      <label class="form-label" for="proceedSelect">Proceed with recompute?</label>
      <select id="proceedSelect" class="form-control">
        <option value="y" selected>y</option>
        <option value="n">n</option>
      </select>
    </div>
  `;
  dynamicFieldsContainer.innerHTML = html;
}

// Helper to initialize Model & Column Searchable Selects
function initModelAndColumnSearchables(initialModel = '', initialColumn = '') {
  const modelNames = Object.keys(modelsData).sort();

  modelSearchable = new SearchableSelect({
    containerId: 'modelSearchable',
    options: modelNames,
    value: initialModel,
    placeholder: 'Search model (93 models available)...',
    allowCustom: true,
    onChange: (selectedModel) => {
      // Reset Column to empty and load options for the selected model
      const availableCols = modelsData[selectedModel] || [];
      if (columnSearchable) {
        columnSearchable.setOptions(availableCols, '');
      }

      // Reset ID input field if present
      const idInput = document.getElementById('idInput');
      if (idInput) idInput.value = '';

      // Reset IDs input field if present
      const idsInput = document.getElementById('idsInput');
      if (idsInput) idsInput.value = '';

      // Reset New Value input field if present
      const newValueInput = document.getElementById('newValueInput');
      if (newValueInput) newValueInput.value = '';
    }
  });

  const initialCols = initialModel ? (modelsData[initialModel] || []) : [];

  columnSearchable = new SearchableSelect({
    containerId: 'columnSearchable',
    options: initialCols,
    value: initialColumn,
    placeholder: initialModel ? 'Search column...' : 'Select a model first...',
    allowCustom: true
  });
}

// Global Preset Setter
window.setPresetValue = function(inputId, val) {
  const el = document.getElementById(inputId);
  if (el) {
    el.value = val;
    el.focus();
  }
};

// Getters for Values
function getCompanyValue() {
  if (companySelect.value === 'CUSTOM') {
    return customCompanyInput.value.trim() || 'INGCO/WADFOW';
  }
  return companySelect.value;
}

function getModelValue() {
  return modelSearchable ? modelSearchable.getValue() : '';
}

function getColumnValue() {
  return columnSearchable ? columnSearchable.getValue() : '';
}

// GENERATE SEEDER RUN LOGIC
function generateSeederRun() {
  const company = getCompanyValue();
  const seederType = seederTypeSearchable ? seederTypeSearchable.getValue() : 'UpdateTable';
  let lines = [];

  switch (seederType) {
    case 'UpdateTable': {
      const model = getModelValue();
      const column = getColumnValue();
      const id = (document.getElementById('idInput')?.value || '').trim();
      const newValue = (document.getElementById('newValueInput')?.value || '').trim();

      if (!model || !column || !id) {
        showToast('Please fill in model, column, and id fields', 'error');
        return;
      }

      lines.push(company);
      lines.push(`Seeder: UpdateTable`);
      lines.push(`model: ${model}`);
      lines.push(`column: ${column}`);
      lines.push(`id: ${id}`);
      lines.push(`new value: ${newValue}`);
      break;
    }

    case 'UpdatePoPrice': {
      const poId = (document.getElementById('poIdInput')?.value || '').trim();
      const poItemId = (document.getElementById('poItemIdInput')?.value || '').trim();
      const newDrPrice = (document.getElementById('newDrPriceInput')?.value || '').trim();
      const basesOfQuantity = document.getElementById('basesOfQuantitySelect')?.value || '1';
      const wantToRecompute = document.getElementById('wantToRecomputeSelect')?.value || 'y';
      const proceed = document.getElementById('proceedSelect')?.value || 'y';

      lines.push(company);
      lines.push(`Seeder: UpdatePoPrice`);
      lines.push(`po id: ${poId}`);
      lines.push(`po item id : ${poItemId}`);
      lines.push(`new dr price: ${newDrPrice}`);
      lines.push(`bases of quantity [0/1]: ${basesOfQuantity}`);
      lines.push(`want to recompute item amount to pay?: ${wantToRecompute}`);
      lines.push(`proceed?: ${proceed}`);
      break;
    }

    case 'UpdateWarehouseOfReceivedPO': {
      const poId = (document.getElementById('poIdInput')?.value || '').trim();
      const warehouseId = (document.getElementById('warehouseIdInput')?.value || '').trim();

      lines.push(company);
      lines.push(`Seeder: UpdateWarehouseOfReceivedPO`);
      lines.push(`po_id: ${poId}`);
      lines.push(`warehouse_id: ${warehouseId}`);
      break;
    }

    case 'UpdateTableSameColumnMultipleRecords': {
      const model = getModelValue();
      const column = getColumnValue();
      const ids = (document.getElementById('idsInput')?.value || '').trim();
      const newValue = (document.getElementById('newValueInput')?.value || '').trim();

      lines.push(company);
      lines.push(`Seeder: UpdateTableSameColumnMultipleRecords`);
      lines.push(`model: ${model}`);
      lines.push(`column: ${column}`);
      lines.push(`ids: ${ids}`);
      lines.push(`new value: ${newValue}`);
      break;
    }

    case 'DeleteFasBasketAndRequisitionConnection': {
      const fasBasketId = (document.getElementById('fasBasketIdInput')?.value || '').trim();

      lines.push(company);
      lines.push(`Seeder: DeleteFasBasketAndRequisitionConnection`);
      lines.push(`admin_fas_basket_id: ${fasBasketId}`);
      break;
    }

    case 'ManualRevertOfPurchaseBasket': {
      const ids = (document.getElementById('idsInput')?.value || '').trim();

      lines.push(company);
      lines.push(`seeder: ManualRevertOfPurchaseBasket`);
      lines.push(`ids: ${ids}`);
      break;
    }

    case 'PoRecomputeTotalOrAmountToPay': {
      const poIds = (document.getElementById('poIdsInput')?.value || '').trim();
      const recomputeOpt = document.getElementById('recomputeOptionSelect')?.value || '2';
      const quantityBasis = document.getElementById('quantityBasisSelect')?.value || '1';
      const proceed = document.getElementById('proceedSelect')?.value || 'y';

      lines.push(company);
      lines.push(`Seeder: PoRecomputeTotalOrAmountToPay`);
      lines.push(`Po Ids: ${poIds}`);
      lines.push(`What do you want to recompute?: ${recomputeOpt}`);
      lines.push(`What quantity will be the basis for recompute?: ${quantityBasis}`);
      lines.push(`Proceed with recompute?: ${proceed}`);
      break;
    }
  }

  const newChunkText = lines.join('\n');

  historyStack.push(notepadTextarea.value);
  undoBtn.disabled = false;

  if (notepadTextarea.value.trim().length > 0) {
    notepadTextarea.value += '\n\n' + newChunkText;
  } else {
    notepadTextarea.value = newChunkText;
  }

  saveNotepadToStorage();
  notepadTextarea.scrollTop = notepadTextarea.scrollHeight;
  showToast(`Appended ${seederType} to notepad!`, 'success');
}

// Update Entry Counter
function updateEntryCounter() {
  const text = notepadTextarea.value.trim();
  if (!text) {
    entryCounter.textContent = '0 entries';
    return;
  }
  const matches = text.match(/(Seeder:|seeder:)/gi);
  const count = matches ? matches.length : 1;
  entryCounter.textContent = `${count} ${count === 1 ? 'entry' : 'entries'}`;
}

// Toast Notification
function showToast(message, type = 'info') {
  const toast = document.createElement('div');
  toast.className = `toast ${type}`;
  toast.innerHTML = `<span>${message}</span>`;
  toastContainer.appendChild(toast);

  setTimeout(() => {
    toast.style.opacity = '0';
    setTimeout(() => { toast.remove(); }, 200);
  }, 2200);
}

// MODEL MANAGEMENT MODAL FUNCTIONS
function openModelsModal() {
  renderModelListInModal();
  modelsModal.classList.remove('hidden');
}

function closeModelsModal() {
  modelsModal.classList.add('hidden');
}

function renderModelListInModal() {
  modelList.innerHTML = '';
  Object.keys(modelsData).sort().forEach(modelName => {
    const columns = modelsData[modelName];
    const card = document.createElement('div');
    card.className = 'model-item-card';
    card.dataset.model = modelName;

    card.innerHTML = `
      <div class="model-item-header">
        <input type="text" class="form-control model-name-input" value="${modelName}">
        <button class="btn btn-sm btn-danger-ghost" onclick="removeModelFromModal('${modelName}')">Remove</button>
      </div>
      <div class="column-tags-wrapper">
        ${columns.map(col => `
          <span class="column-tag">
            ${col}
            <button onclick="removeColumnFromModal('${modelName}', '${col}')">&times;</button>
          </span>
        `).join('')}
      </div>
      <div class="add-column-form">
        <input type="text" class="form-control add-column-input" placeholder="+ Add column..." onkeydown="if(event.key==='Enter'){ handleAddColumnTag('${modelName}', this); }">
        <button class="btn btn-sm btn-secondary" onclick="handleAddColumnBtn('${modelName}', this)">Add</button>
      </div>
    `;
    modelList.appendChild(card);
  });
}

window.removeModelFromModal = function(modelName) {
  delete modelsData[modelName];
  renderModelListInModal();
};

window.removeColumnFromModal = function(modelName, colName) {
  if (modelsData[modelName]) {
    modelsData[modelName] = modelsData[modelName].filter(c => c !== colName);
    renderModelListInModal();
  }
};

window.handleAddColumnTag = function(modelName, inputEl) {
  const val = inputEl.value.trim();
  if (val && modelsData[modelName]) {
    if (!modelsData[modelName].includes(val)) {
      modelsData[modelName].push(val);
    }
    renderModelListInModal();
  }
};

window.handleAddColumnBtn = function(modelName, btnEl) {
  const inputEl = btnEl.previousElementSibling;
  handleAddColumnTag(modelName, inputEl);
};

function handleAddNewModel() {
  const newModelName = prompt('Enter new Model Name (e.g. AdminCustomer):');
  if (newModelName && newModelName.trim()) {
    const trimmed = newModelName.trim();
    if (!modelsData[trimmed]) {
      modelsData[trimmed] = ['id', 'created_at', 'updated_at'];
      renderModelListInModal();
    } else {
      showToast('Model already exists', 'error');
    }
  }
}

function handleResetModels() {
  if (confirm('Reset models and columns dictionary to reference defaults (93 models)?')) {
    modelsData = JSON.parse(JSON.stringify(DEFAULT_MODELS_DATA));
    saveModelsDataToStorage();
    renderModelListInModal();
    renderDynamicFields();
    showToast('Reset to 93 reference models', 'info');
  }
}

function handleSaveModelsModal() {
  const newDictionary = {};
  const cards = modelList.querySelectorAll('.model-item-card');
  cards.forEach(card => {
    const oldName = card.dataset.model;
    const nameInput = card.querySelector('.model-name-input');
    const newName = nameInput.value.trim();
    if (newName) {
      newDictionary[newName] = modelsData[oldName] || [];
    }
  });

  modelsData = newDictionary;
  saveModelsDataToStorage();
  closeModelsModal();
  renderDynamicFields();
  showToast('Saved Models & Columns dictionary!', 'success');
}

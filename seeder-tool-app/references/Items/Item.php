<?php

namespace App\Models\Items;

use App\Models\ItemAccessory;
use App\Models\ItemBrandSuggestion;
use App\Traits\HasAttributeChangesToBroadcast;
use App\Traits\MorphMap;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Items\ItemCategory;
use App\Models\Items\ItemSubstitution;
use App\Models\Items\ItemType;
use App\Models\Items\ItemUom;
use App\Models\Operations\WarehouseItemStocks;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\Service\ScRepairFormItem;
use App\Models\Service\ScRfEstItem;
use App\Models\Items\ItemSpareParts;
use App\Models\SampleFormItem;
use App\Models\System;
use App\Traits\Caching;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Item extends Model implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable, MorphMap, HasAttributeChangesToBroadcast, Caching;

    protected $appends = [
        'morphMapKey',
        'item_type_is_standard',
        'item_type_text',
        'item_category_text',
        'item_uom_text',
        'item_brand_text',
        'item_size_text',
        'suggested_items',
        'substitution_items',
        'default_warehouse_stocks',
        'non_default_warehouse_stocks',
        'image_path_pricelist',
        'item_category_code',
        'accessories',
        'item_brand_suggestion',
        'cordless',
    ];

    protected $fillable = [
        'model',
        'name',
        'description',
        'type_id',
        'category_id',
        'uom_id',
        'selling_price',
        'sp_price',
        'foc',
        'barcode',
        'active',
        'master_box_qty',
        'master_box_barcode',
        'small_box_qty',
        'small_box_barcode',
        'moq',
        'moq_po',
        'cbm',
        'weight',
        'brand_id',
        'deactivate_reason',
        'activate_reason',
        'part_num',
        'image_path',
        'exclude_in_pricelist',
        'baklas_qty',
        'remarks',
        'with_expiration',
        'cordless_voltage',
        'consumable',
        'requires_serial',
        'not_eligible_for_rebate',
    ];

    public function itemType()
    {
        return $this->belongsTo(ItemType::class, 'type_id')->withTrashed();
    }

    public function itemCategory()
    {
        return $this->belongsTo(ItemCategory::class, 'category_id')->withTrashed();
    }

    public function itemUom()
    {
        return $this->belongsTo(ItemUom::class, 'uom_id')->withTrashed();
    }

    public function itemBrand()
    {
        return $this->belongsTo(ItemBrand::class, 'brand_id')->withTrashed();
    }

    public function itemSize(): BelongsTo
    {
        return $this->belongsTo(ItemSize::class, 'size_id')->withTrashed();
    }

    public function itemSuggestions(): HasMany
    {
        return $this->hasMany(ItemSuggestion::class);
    }

    public function itemSubstitutions(): HasMany
    {
        return $this->hasMany(ItemSubstitution::class, 'new_item_id')->orderBy('ranking');
    }

    public function itemSubstitutionsAsOldModel(): HasMany
    {
        return $this->hasMany(ItemSubstitution::class, 'item_id')->orderBy('ranking');
    }

    public function warehouseItemStocks(): HasMany
    {
        return $this->hasMany(WarehouseItemStocks::class);
    }

    public function sc_repair_form_items(): HasMany
    {
        return $this->hasMany(ScRepairFormItem::class, 'item_id');
    }

    public function sc_rf_est_items(): HasMany
    {
        return $this->hasMany(ScRfEstItem::class, 'item_id');
    }

    public function item_spare_parts(): HasMany
    {
        return $this->hasMany(ItemSpareParts::class, 'item_id');
    }

    public function itemAccessories(): HasMany
    {
        return $this->hasMany(ItemAccessory::class);
    }

    public function itemBrands(): HasMany
    {
        return $this->hasMany(ItemBrandSuggestion::class);
    }

    public function getTotalStocksAttribute(){
        $total = 0;
        if(!empty($this->warehouseItemStocks)){
            foreach($this->warehouseItemStocks as $ws){
                if(!empty($ws->warehouse) && $ws->warehouse->virtual == 0){
                    $total += $ws->quantity;
                }
            }
        }
        unset($this->warehouseItemStocks);
        return $total;
    }

    public function getDefaultWarehouseStocksAttribute(){
        $total = 0;
        if(!empty($this->warehouseItemStocks)){
            foreach($this->warehouseItemStocks as $ws){
                if(!empty($ws->warehouse) && $ws->warehouse->virtual == 0 && $ws->warehouse->default == 1 && $ws->warehouse->actual == 1 && $ws->warehouse->active == 1){
                    $total += $ws->quantity;
                    continue;
                }
            }
        }
        unset($this->warehouseItemStocks);
        return $total;
    }

    public function getNonDefaultWarehouseStocksAttribute(){
        $total = 0;
        if (!empty($this->warehouseItemStocks)) {
            foreach ($this->warehouseItemStocks as $ws) {
                if (!empty($ws->warehouse) && $ws->warehouse->virtual == 0 && $ws->warehouse->default == 0 && $ws->warehouse->actual == 1 && $ws->warehouse->active == 1) {
                    $total += $ws->quantity;
                    continue;
                }
            }
        }
        unset($this->warehouseItemStocks);
        return $total;
    }

    public function getItemTypeIsStandardAttribute(): bool
    {
        if (empty($this->type_id)) {
            return false;
        }

        return !empty($this->itemType) ? (bool) $this->itemType->is_standard : false;
    }

    public function getItemTypeTextAttribute(){
        $item_type_text = "";
        if(!empty($this->type_id)){
            $item_type_text = !empty($this->itemType) ? $this->itemType->name : '';
        }
        unset($this->itemType);

        return $item_type_text;
    }

    public function getItemCategoryTextAttribute(){
        $item_category_text = "";
        if(!empty($this->category_id)){
            $item_category_text = !empty($this->itemCategory) ? $this->itemCategory->name : '';
        }
        unset($this->itemCategory);

        return $item_category_text;
    }

    public function getItemCategoryCodeAttribute(){
        $item_category_text = "";
        if(!empty($this->category_id)){
            $item_category_text = !empty($this->itemCategory) ? $this->itemCategory->code : '';
        }
        unset($this->itemCategory);

        return $item_category_text;
    }

    public function getCordlessAttribute(){
        $cordless = 0;
        if(!empty($this->cordless_voltage) && $this->cordless_voltage !== 'null' && $this->cordless_voltage !== ''){
            $cordless = 1;
        }

        return $cordless;
    }

    public function getItemUomTextAttribute(){
        $item_uom_text = "";
        if(!empty($this->uom_id)){
            $item_uom_text = !empty($this->itemUom) ? $this->itemUom->name : '';
        }
        unset($this->itemUom);

        return $item_uom_text;
    }

    public function getItemBrandTextAttribute(){
        $item_brand_text = '';
        if (!empty($this->brand_id)) {
            $item_brand_text = !empty($this->itemBrand) ? $this->itemBrand->name : '';
        }
        unset($this->itemBrand);

        return $item_brand_text;
    }

    public function getItemSizeTextAttribute(){
        $item_size_text = '';
        if (!empty($this->size_id)) {
            $item_size_text = !empty($this->itemSize) ? $this->itemSize->name : '';
        }
        unset($this->itemSize);

        return $item_size_text;
    }

    public function getSuggestedItemsAttribute(){
        $suggested_items = [];

        if(!empty($this->itemSuggestions)){
            foreach($this->itemSuggestions as $itemSuggestion){
                $sug_item = Item::getQuery()->select('id', 'id as value', 'model as text', 'name', 'description', 'selling_price', 'image_path')->where('id', $itemSuggestion->new_item_id)->first();
                if($sug_item){
                    array_push($suggested_items, $sug_item);
                }
            }
        }
        return $suggested_items;
    }

    public function getSubstitutionItemsAttribute(){
        $substitution_items = [];

        if(!empty($this->itemSubstitutionsAsOldModel)){
            foreach($this->itemSubstitutionsAsOldModel as $itemSubstitution){
                $sub_item = DB::table('items')->select('id', 'id as value', 'model as text', 'name', 'description', 'selling_price', 'image_path')->where('id', $itemSubstitution->new_item_id)->first();
                if($sub_item){
                    array_push($substitution_items, $sub_item);
                }
            }
        }
        return $substitution_items;
    }

    public function getSubstitutionItemsWithStocksAttribute(){
        $substitution_items = [];

        if(!empty($this->itemSubstitutions)){
            foreach($this->itemSubstitutions as $itemSubstitution){
                $sub_item = DB::table('items')->select('items.id as value', 'items.model as text', 'items.name',
                                DB::raw("SUM(warehouse_item_stocks.quantity) as total_stocks"))
                                ->leftJoin('warehouse_item_stocks', 'items.id', '=', 'warehouse_item_stocks.item_id')
                                ->leftJoin('warehouses', 'warehouse_item_stocks.warehouse_id', '=', 'warehouses.id')
                                ->where('warehouses.virtual', 0)
                                ->where('items.id', $itemSubstitution->item_id)
                                ->groupBy('warehouse_item_stocks.item_id')
                                ->first();
                if($sub_item){
                    array_push($substitution_items, $sub_item);
                }
            }
        }
        return $substitution_items;
    }

    public function getImagePathPricelistAttribute()
    {
        $imageFilename = $this->model . substr($this->image_path, -4);
        $filePath = 'items/pricelist/' . $imageFilename;
        $hasImage = Storage::disk('pricelist_images')->exists($imageFilename);

        return $hasImage ? 'images/' . $filePath : 'images/noimage.jpg';
    }

    public function sample_form_items()
    {
        return $this->hasMany(SampleFormItem::class, 'item_id');
    }

    public static function boot()
    {
        parent::boot();
        $cache_names = [
            'items_dropdown',
            'service_items_dropdown',
            'items_excluded_sp',
            'item_selection_exclude_sp',
            'sp_replacement_selection',
            'specific_list_selection',
            'unassigned_spare_parts_selection',
            'get_items_cm',
            'get_pl_items',
            'customer_items',
            'checklist',
            'bundle_dropdown',
            'get_items_dropdown',
            'cordless_voltage_dropdown',
        ];
        static::created((function ($model) use($cache_names) {
            $model->forget($cache_names);
        }));
        static::updated((function ($model) use($cache_names) {
            $model->forget($cache_names);
        }));
        static::deleted((function ($model) use($cache_names) {
            $model->forget($cache_names);
        }));
    }

    public function getAccessoriesAttribute(){
        $accessories = [];

        if(!empty($this->itemAccessories)){
            foreach($this->itemAccessories as $itemAccessory){
                $acc_item = Item::getQuery()->select('id', 'id as value', 'model as text', 'name', 'description', 'selling_price', 'image_path')->where('id', $itemAccessory->new_item_id)->first();
                if($acc_item){
                    array_push($accessories, $acc_item);
                }
            }
        }
        return $accessories;
    }

    public function getItemBrandSuggestionAttribute() {
        $item_brands = collect();
        if(!empty($this->itemBrands)){
            foreach($this->itemBrands as $itemBrand){
                $system = System::find($itemBrand->system_id);
                $acc_item = DB::connection($system->connection_name)->table('items')->select('id', 'id as value', 'model as text', 'name', 'description', 'selling_price', 'image_path', DB::raw($system->id.' as system_id'))->where('id', $itemBrand->ref_item_id)->first();
                if($acc_item){
                    $item_brands->push($acc_item);
                }
            }
        }
        return $item_brands->groupBy('system_id');
    }
}

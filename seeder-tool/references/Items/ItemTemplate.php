<?php

namespace App\Models\Items;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;
use App\Models\Operations\Warehouse;
use App\Models\Items\ItemTemplateDetails;
class ItemTemplate extends Model implements Auditable
{
    use HasFactory, AuditableTrait;

    protected $fillable = [
        'warehouse_id',
        'name'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    protected $appends =[
        'template_number'
    ];

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }

    public function details(): HasMany
    {
        return $this->hasMany(ItemTemplateDetails::class, 'item_template_id');
    }

    public function getTemplateNumberAttribute()
    {
        $warehouseCode = strtoupper($this->warehouse->code);

        return "TMP# {$warehouseCode}{$this->id}";
    }
}

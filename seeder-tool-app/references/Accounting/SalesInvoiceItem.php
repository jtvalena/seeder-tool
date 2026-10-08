<?php

namespace App\Models\Accounting;

use App\Models\Items\Item;
use App\Models\Sales\PackingListItem;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesInvoiceItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'sales_invoice_id',
        'item_id',
        'quantity',
        'unit_price',
        'total_amount',
        'original_amount',
        'declared_amount',
    ];

    public function salesInvoice()
    {
        return $this->belongsTo(SalesInvoice::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function packingListItems()
    {
        return $this->belongsToMany(
            PackingListItem::class,
            'sales_invoice_item_packing_list_items',
            'sales_invoice_item_id',
            'packing_list_item_id'
        )->withPivot('packing_list_id')->withTimestamps();
    }
}





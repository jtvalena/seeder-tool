<?php

namespace App\Models;

use App\Models\Sales\PackingList;
use App\Models\Accounting\SalesInvoiceItem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;


class SalesInvoiceItemPackingListItems extends Model implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'sales_invoice_item_id',
        'packing_list_id',
        'packing_list_item_id',
    ];

    public function salesInvoiceItem()
    {
        return $this->belongsTo(SalesInvoiceItem::class);
    }

    public function packingList()
    {
        return $this->belongsTo(PackingList::class);
    }
}

<?php

namespace App\Models;

use App\Models\Sales\PackingList;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalesIncentivePackingList extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'sales_incentive_packing_lists';

    protected $fillable = [
        'sales_incentive_detail_id',
        'packing_list_id',
        'packing_list_num',
        'packing_list_date',
        'remarks',
        'total_amount',
    ];

    protected $casts = [
        'packing_list_date' => 'date',
        'total_amount' => 'decimal:2',
    ];

    /**
     * Get the sales incentive detail
     */
    public function salesIncentiveDetail()
    {
        return $this->belongsTo(SalesIncentiveDetail::class, 'sales_incentive_detail_id');
    }

    /**
     * Get the packing list
     */
    public function packingList()
    {
        return $this->belongsTo(PackingList::class, 'packing_list_id');
    }
}

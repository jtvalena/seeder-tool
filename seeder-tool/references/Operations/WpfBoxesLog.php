<?php

namespace App\Models\Operations;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class WpfBoxesLog extends Model
{
    protected $table = 'wpf_boxes_logs';

    protected $fillable = [
        'wpf_id',
        'previous_boxes',
        'changed_by',
    ];

    public function wpf()
    {
        return $this->belongsTo(WarehousePreparationList::class, 'wpf_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}

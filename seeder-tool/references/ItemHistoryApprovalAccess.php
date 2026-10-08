<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemHistoryApprovalAccess extends Model
{
    protected $fillable = [
        'user_id',
        'request_for_deactivation',
        'request_for_activation',
        'price_changes_srp',
        'price_changes_sp',
        'master_box_qty',
        'small_box_qty',
        'moq',
        'model',
        'name',
        'type',
        'category',
    ];

    protected $casts = [
        'request_for_deactivation' => 'boolean',
        'request_for_activation'   => 'boolean',
        'price_changes_srp'        => 'boolean',
        'price_changes_sp'         => 'boolean',
        'master_box_qty'           => 'boolean',
        'small_box_qty'            => 'boolean',
        'moq'                      => 'boolean',
        'model'                    => 'boolean',
        'name'                     => 'boolean',
        'type'                     => 'boolean',
        'category'                 => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

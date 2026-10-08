<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemHistory extends Model
{
    protected $fillable = [
        'item_id',
        'criteria',
        'column',
        'data_type',
        'old_value',
        'new_value',
        'status',
        'requested_by',
        'approved_by',
        'approved_at',
        'rejected_by',
        'rejected_at',
        'reject_reason',
    ];

    protected $appends = [
        'requested_by_name',
        'approved_by_name',
        'rejected_by_name',
        'criteria_text',
        'status_text',
    ];

    public function requested_by_user()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function approved_by_user()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function rejected_by_user()
    {
        return $this->belongsTo(User::class, 'rejected_by');
    }

    public function item_history_criteria()
    {
        return $this->belongsTo(ItemHistoryCriteria::class, 'criteria', 'value');
    }

    public function getRequestedByNameAttribute()
    {
        return $this->requested_by_user->name ?? '';
    }

    public function getApprovedByNameAttribute()
    {
        return $this->approved_by_user->name ?? '';
    }

    public function getRejectedByNameAttribute()
    {
        return $this->rejected_by_user->name ?? '';
    }

    public function getCriteriaTextAttribute()
    {
        return $this->item_history_criteria->text ?? '';
    }

    public function getStatusTextAttribute()
    {
        $status_text = 'unknown';

        switch ($this->status) {
            case 0:
                $status_text = 'for approval';
                break;
            case 1:
                $status_text = 'approved';
                break;
            case 2:
                $status_text = 'rejected';
                break;
            default:
                $status_text = 'unknown';
                break;
        }

        return $status_text;
    }
}

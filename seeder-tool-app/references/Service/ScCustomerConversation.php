<?php

namespace App\Models\Service;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use App\Model\Customer;

class ScCustomerConversation extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'customer_id',
    ];

    public function customer() {
        return $this->belongsTo(Customer::class);
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $latest = ScCustomerConversation::select('ticket_number')->whereRaw('length(ticket_number)=7')->max('ticket_number');
            $latest = str_replace('CC', '', $latest);
            if (!empty($latest)) {
                $next_ticket_num = ($latest != null) ? intval($latest) + 1 : 1;
                $model->ticket_number = 'CC' . str_pad((string)$next_ticket_num, 5, '0', STR_PAD_LEFT);
            } else {
                $model->ticket_number = 'CC' . str_pad('1', 5, '0', STR_PAD_LEFT);
            }
        });
    }
}

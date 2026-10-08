<?php

namespace App\Models\Service;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use App\Model\ScCustomerConversation;

class ScCustomerMessage extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'source',
        'sc_customer_conversation_id',
    ];

    public function sc_customer_conversation() {
        return $this->belongsTo(ScCustomerConversation::class);
    }
}

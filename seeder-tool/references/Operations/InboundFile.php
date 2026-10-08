<?php

namespace App\Models\Operations;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Operations\Inbound;
use Illuminate\Database\Eloquent\Model;

class InboundFile extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'path',
    ];

    public function inbound() {
        return $this->belongsTo(Inbound::class);
    }
}

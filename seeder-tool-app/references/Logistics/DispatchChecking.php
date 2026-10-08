<?php

namespace App\Models\Logistics;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class DispatchChecking extends Model
{
    use HasFactory;

    protected $table = 'dispatch_checking';

    protected $fillable = [
        'dispatch_id',
        'user_id',
    ];

    public function dispatch()
    {
        return $this->belongsTo(Dispatch::class, 'dispatch_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

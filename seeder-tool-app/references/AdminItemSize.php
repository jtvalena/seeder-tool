<?php

namespace App\Models;

use App\Traits\Caching;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminItemSize extends Model
{
    use HasFactory, Caching;

    protected $table = 'admin_item_sizes';

    protected $fillable = [
        'code',
        'name',
        'description',
        'active',
    ];
}

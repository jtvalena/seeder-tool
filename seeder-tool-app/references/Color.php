<?php

namespace App\Models;

use App\Traits\Caching;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Color extends Model
{
    use HasFactory, Caching;

    protected $table = 'colors';

    protected $fillable = [
        'name',
        'hex_code',
    ];
}

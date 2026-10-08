<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SSSContributionTable extends Model
{
    use HasFactory;
    protected $table = 'sss_contribution_tables';

    protected $fillable = [
        'compensation_from',
        'compensation_to',
        'msc_regular_ss_ec',
        'msc_wisp',
        'msc_total',
        'regular_ss_er',
        'regular_ss_ee',
        'regular_ss_total',
        'ec_er',
        'ec_ee',
        'ec_total',
        'wisp_er',
        'wisp_ee',
        'wisp_total',
        'er_total',
        'ee_total',
        'total',
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Support\Facades\Auth;

class IndentOrderFile extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable, SoftDeletes;

    protected $fillable = [
        'indent_order_id',
        'name',
        'path',
        'uploaded_by',
        'type',
        'extension',
    ];

    protected $appends = ['uploaded_by_name'];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    public function indent_order()
    {
        return $this->belongsTo(IndentOrder::class);
    }

    public function uploaded_by_user()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function getUploadedByNameAttribute()
    {
        return User::where('id', $this->uploaded_by)->value('name');
    }

    // Methods required for FileUploadComponent (Utils version)
    public function getPath()
    {
        return 'sales/indent_orders';
    }

    public function getDisk()
    {
        return 'indent_orders';
    }

    public function getFillables($request)
    {
        $this->indent_order_id = $request['m_id'];
        $this->uploaded_by = Auth::user()->id ?? 0;
        $this->name = $request['file_name'] ?? '';
        $this->path = $request['filePath'] ?? '';
        $this->type = $request['type'] ?? '';
        $this->extension = $request['extension'] ?? '';
        return $this;
    }

    public function getRelationId()
    {
        return 'indent_order_id';
    }
}

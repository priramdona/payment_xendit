<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class XenditDisbursementChannel extends Model
{

    use HasFactory;
    use HasUuids;
    use SoftDeletes;

    protected $guarded = [];

    public function XenditDisbursementMethod() {
        return $this->belongsTo(XenditDisbursementMethod::class, 'xendit_disbursement_method_id', 'id');
    }
}

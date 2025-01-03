<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class XenditDisbursement extends Model
{

    use HasFactory;
    use HasUuids;
    use SoftDeletes;

    protected $guarded = [];
    protected $casts = [
        'channel_properties' => 'array',
        'receipt_notification' => 'array',
    ];

    // public function businessAmount()
    // {
    //     return $this->morphOne(businessAmount::class, 'transactional');
    // }
}

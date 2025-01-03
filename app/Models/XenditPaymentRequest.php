<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class XenditPaymentRequest extends Model
{

    use HasFactory;
    use HasUuids;
    use SoftDeletes;

    protected $guarded = [];
    public function xenditCreatePayment()
    {
        return $this->morphOne(XenditCreatePayment::class, 'transactional');
    }
}

<?php

namespace App;

use App\Constants\PromoType;
use Illuminate\Database\Eloquent\Model;
use Throwable;

use function GuzzleHttp\json_decode;

class Promo extends Model
{
    protected $table = 'promo';

    protected $fillable = [
        'code',
        'type',
        'value',
        'expires_on',
        'used_many_times',
        'combo_courses'
    ];

    protected $dates = [
        'expires_on'
    ];

    protected $casts = [
        'expires_on' => 'datetime:Y-m-d H:i',
        'combo_courses' => 'array'
    ];

    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'promo_id', 'id');
    }
}

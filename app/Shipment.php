<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Shipment extends Model
{
    protected $table = 'shipments';

    protected $fillable = [
        'partner',
        'id_partner',
        'status',
        'pick_date',
        'deliver_date',
        'ship_money',
        'is_fast',
        'is_ship_cod',
        'invoice_id'
    ];

    protected $dates = [
        'pick_date',
        'deliver_date'
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id', 'id');
    }
}

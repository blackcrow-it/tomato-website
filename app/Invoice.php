<?php

namespace App;

class Invoice extends BaseModel
{
    protected $table = 'invoices';

    protected $fillable = [
        'user_id'
    ];

    public function items()
    {
        return $this->hasMany(InvoiceItem::class, 'invoice_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function promo()
    {
        return $this->belongsTo(Promo::class, 'promo_id', 'id');
    }

    public function shipment()
    {
        return $this->hasOne(Shipment::class, 'invoice_id', 'id');
    }
}

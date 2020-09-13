<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
    protected $table = 'invoice_items';

    protected $fillable = [
        'invoice_id', 'object_id', 'amount', ' price'
    ];

    public function invoice()
    {
        return $this->belongsTo('App\Invoice', 'invoice_id', 'id');
    }
}

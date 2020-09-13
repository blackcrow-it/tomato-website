<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $table = 'invoices';

    protected $fillable = [
        'user_id'
    ];

    public function items()
    {
        return $this->hasMany('App\InvoiceItem', 'invoice_id', 'id');
    }
}

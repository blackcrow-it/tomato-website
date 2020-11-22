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

    public function book()
    {
        return $this->belongsTo(Book::class, 'object_id', 'id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'object_id', 'id');
    }
}

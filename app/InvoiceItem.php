<?php

namespace App;

class InvoiceItem extends BaseModel
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

    public function comboCourse()
    {
        return $this->belongsTo(ComboCourses::class, 'object_id', 'id');
    }
}

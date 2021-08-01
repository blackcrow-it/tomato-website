<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Survey extends Model
{
    protected $table = 'part_survey';

    protected $fillable = [
        'part_id',
        'full_name',
        'email',
        'phone_number',
        'birthday',
        'is_received',
        'is_read',
        'is_student',
        'received_by_user_id',
        'data',
        'created_at',
    ];

    protected $casts = [
        'data' => 'array'
    ];

    public function part()
    {
        return $this->belongsTo(Part::class, 'part_id', 'id');
    }

    public function received_by()
    {
        return $this->belongsTo(User::class, 'received_by_user_id', 'id');
    }
}

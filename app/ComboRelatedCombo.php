<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ComboRelatedCombo extends Model
{
    protected $table = 'combo_course_related_combo_course';
    protected $fillable = [
        'combo_course_id', 'related_combo_course_id'
    ];

    public function combo_course()
    {
        return $this->belongsTo(ComboCourses::class, 'combo_course_id', 'id');
    }

    public function related_combo_course()
    {
        return $this->belongsTo(ComboCourses::class, 'related_combo_course_id', 'id');
    }
}

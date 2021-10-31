<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ZoomMeeting extends Model
{
    protected $table = 'zoom_meetings';

    protected $fillable = [
        'meeting_id', 'topic', 'type', 'start_time', 'duration', 'password', 'agenda', 'occurrences',
        'tracking_fields', 'recurrence', 'settings', 'join_url', 'start_url', 'is_start', 'owner_id'
    ];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id', 'id');
    }
}

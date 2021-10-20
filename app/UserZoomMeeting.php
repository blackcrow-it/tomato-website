<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserZoomMeeting extends Model
{
    protected $table = 'user_zoom_meetings';

    protected $fillable = [
        'user_id', 'zoom_meeting_id'
    ];

    public function zoomMeeting()
    {
        return $this->belongsTo(ZoomMeeting::class, 'zoom_meeting_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}

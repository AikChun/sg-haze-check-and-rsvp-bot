<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Attendee;

class Event extends Model
{
    //
    public function attendees()
    {
        return $this->hasMany(Attendee::class, 'event_id', 'used_id');
    }

}

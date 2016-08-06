<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Attendee;

class Event extends Model
{
    protected $fillable = [
        'chat_id',
        'description'
    ];
    //
    public function attendees()
    {
        return $this->hasMany(Attendee::class, 'event_id', 'used_id');
    }

}

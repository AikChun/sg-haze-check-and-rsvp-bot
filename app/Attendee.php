<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Attendee extends Model
{
    protected $fillable = [
        'event_id',
        'user_id',
        'username',
        'counter'
    ];
    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}

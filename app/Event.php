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
        return $this->hasMany(Attendee::class);
    }

    public function printEventDetails()
    {
        $text   = "Event: ";
        $text  .= $this->description . "\n\n";
        $attendees = $this->attendees->each(function($item, $key) use (&$text){
            $text  .= $item->username. "\n";
        });

        $totalAttendees = $this->attendees->sum('counter');
        $text .= "\nNumber of attendees: " . $totalAttendees . "\n";
        $text .= "Click here to attend!\n";
        $text .= "/attending";

        return $text;
    }

}

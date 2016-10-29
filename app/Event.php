<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Attendee;
use Telegram\Bot\Objects\User;

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
        $text   = "Event: \n";
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

    public function registerUser(User $user)
    {
        $attended = Attendee::where([
            'user_id' =>  $user->getId(),
            'event_id' => $this->id,
        ])->first();

        if($attended) {
            return true;
        }

        $attendee = new Attendee;

        $username = $user->getFirstName();

        if($user->getUsername() != "") {
            $username = $user->getUsername();
        }

        $attendee->user_id  = $user->getId();
        $attendee->username = $username;
        $attendee->event_id = $this->id;
        $attendee->counter  = 1;

        return $attendee->save();
    }

}

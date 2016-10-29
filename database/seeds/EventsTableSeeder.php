<?php

use Illuminate\Database\Seeder;

class EventsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $events = [ 'Breakfast at Winterfell',  'Lunch at Eyrie', 'Dinner at Storm\'s End'];
        for($i=0;$i<3;$i++) {
            $event              = new App\Event;
            $event->chat_id     = $i+1;
            $event->description = $events[$i];
            if($i == 2) {
                $event->active      = true;
            } else {
                $event->active      = false;
            }
            $event->save();
        }

    }
}

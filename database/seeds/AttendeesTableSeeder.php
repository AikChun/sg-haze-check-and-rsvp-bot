<?php

use Illuminate\Database\Seeder;

class AttendeesTableSeeder extends Seeder
{
    protected $faker;
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //$faker = Faker\Factory::create();
        for($i=1;$i<=3;$i++) {
            $event_id = $i;
            for($j=0;$j<3;$j++) {
                $attendee           = new App\Attendee;
                $attendee->user_id  = $i * 1000 + $j;
                $attendee->event_id = $event_id;
                $attendee->username = $attendee->user_id . 'test_user';
                $attendee->counter  = 1;
                $attendee->save();
            }
        }

    }
}

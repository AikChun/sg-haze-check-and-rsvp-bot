<?php

namespace App\Console\Commands;

use App\HazeCheckBot;
use Illuminate\Console\Command;

class BotGetUpdates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bot:updates';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    protected $bot;
    /**
     * Create a new command instance.
     *
     * @return void
     */

    public function __construct(HazeCheckBot $bot)
    {
        parent::__construct();
        $this->bot = $bot;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->bot->replyToMessages();
    }

}

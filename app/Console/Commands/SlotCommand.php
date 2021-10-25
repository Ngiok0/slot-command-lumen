<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Http\Controllers\SlotController;

class SlotCommand extends \Illuminate\Console\Command
{
    protected $signature = 'slot';
    protected $description = 'Plays a game on a simulated slot machine';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle(): void
    {
        $this->info($this->description);

        $slot = new SlotController();

        $this->comment(json_encode($slot->play(), JSON_PRETTY_PRINT));
    }
}

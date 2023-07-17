<?php

namespace Vanguard\Console\Commands;

use Illuminate\Console\Command;
use Vanguard\Http\Controllers\Web\PotentialProductController;

class spider extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'spider:product';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'crawl product info';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(PotentialProductController $pp)
    {
        $pp->spider();
    }
}

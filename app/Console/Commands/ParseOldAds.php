<?php

namespace App\Console\Commands;
use App\Models\Ads;
use App\Http\Controllers\DBController;
use App\Http\Controllers\CurrencyController;

use Illuminate\Console\Command;

class ParseOldAds extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'parse:olds';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Parses types adt rates for old ads in DB';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $all_posts = Ads::all();
        $posts = new DBController;
        return Command::SUCCESS;
    }
}

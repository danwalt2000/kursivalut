<?php

namespace App\Console\Commands;
use App\Models\Ads;
use App\Http\Controllers\DBController;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\ParseAdsController;

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
    protected $description = 'Parses types and rates for old ads in DB';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $posts = new DBController;
        // $db_posts = Ads::all()->sortByDesc("date");
        $db_posts = Ads::where('rate', '=', 0)->orderBy("date", "desc")->skip(300)->take(10000)->get();
        $parser = new ParseAdsController;
        foreach( $db_posts as $db_post){
            $parser->parseOldAd($db_post);

        }
    }
}

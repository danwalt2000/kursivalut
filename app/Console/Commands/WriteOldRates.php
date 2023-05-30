<?php

namespace App\Console\Commands;
use App\Http\Controllers\DBController;
use App\Http\Controllers\RatesController;

use Illuminate\Console\Command;

class WriteOldRates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rates:old';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Computes average rates of old ads in DB';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $rates_controller = new RatesController;
        $month_ago = time() - 30*24*60*60;
        $rate_time = $month_ago;

        while( $rate_time < time() ){
            $rates_controller->writeRates($rate_time);
            $rate_time += 60*60;
        }
    }
}

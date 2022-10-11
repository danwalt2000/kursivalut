<?php

namespace App\Console;

use App\Http\Controllers\GetAdsController;
use App\Http\Controllers\CurrencyController;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
        foreach( (new CurrencyController)->publics as $name => $value){
            $time = $value["time"];
            $id = $value["id"];

            $schedule->call( function() use ($id){
                GetAdsController::getPosts( $id );
            })->$time()->between('7:00', '18:00');

            $schedule->call( function() use ($id){
                GetAdsController::getPosts( $id );
            })->hourly()->unlessBetween('7:00', '18:00');
        }
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}

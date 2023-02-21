<?php

namespace App\Console;

use App\Http\Controllers\GetAdsController;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\VarsController;
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
        $vars = new VarsController;

        foreach( $vars->publics as $name => $channel ){
            $time = $channel["time"];

            // в рабочее время частота запросов к группам указана в переменной $publics
            $schedule->call( function() use ($channel){
                (new GetAdsController)->getNewAds( $channel );
            })->$time()->between('4:30', '16:00'); // по Гринвичу

            // в нерабоче время обращаться к группам раз в полчаса
            $schedule->call( function() use ($channel){
                (new GetAdsController)->getNewAds( $channel );
            })->everyThirtyMinutes()->unlessBetween('4:30', '16:00'); // по Гринвичу
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

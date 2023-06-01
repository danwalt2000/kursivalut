<?php

namespace App\Console;

use App\Http\Controllers\GetAdsController;
use App\Http\Controllers\RatesController;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Config;

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
        // вычисление среднего курса: в 03 минуты каждого часа 
        // (чтобы не совпадало с другими задачами) 
        // с 07:00 до 19:00 по Москве
        $rates = new RatesController;
        $schedule->call( function() use ($rates){
            $rates->writeRates();
        })->hourlyAt(3)->between('4:00', '16:00'); // по Гринвичу
        
        $schedule->call( function() use ($rates){
            $rates->getStockRates();
        })->hourlyAt(4)->between('4:00', '16:00'); // по Гринвичу

        // сбор новых объявлений
        foreach ( Config::get('locales') as $subdomain => $locale ){
            foreach( $locale['publics'] as $name => $channel ){
                $time = $channel["time"];
    
                // в рабочее время частота запросов к группам указана в переменной $publics
                $schedule->call( function() use ($channel, $locale){
                    (new GetAdsController)->getNewAds( $channel, $locale );
                })->$time()->between('4:30', '16:00'); // по Гринвичу
    
                // в нерабоче время обращаться к группам раз в полчаса
                $schedule->call( function() use ($channel, $locale){
                    (new GetAdsController)->getNewAds( $channel, $locale );
                })->everyThirtyMinutes()->unlessBetween('4:30', '16:00'); // по Гринвичу
            }
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

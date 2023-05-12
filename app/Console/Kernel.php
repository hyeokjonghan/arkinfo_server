<?php

namespace App\Console;

use App\Http\Controllers\Arknights\SetDataController;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $today = date("Y-m-d");
        // 백업, 10분뒤 동기화 처리
        $schedule->exec('mongodump -d arknights_info -o ~/dump/'.$today)
        ->dailyAt('0:00');

        $schedule->job(function() {
            $setDataController = new SetDataController();
            $setDataController->setBuildingsSync();
            $setDataController->setItemSync();
            $setDataController->setOperatorKey();
            $setDataController->setCharSync();
        })->dailyAt('0:10');


        // 백업된 DB 삭제 처리
        $schedule->exec('find ~/dump -mtime +6 -delete')
        ->dailyAt('0:30');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}

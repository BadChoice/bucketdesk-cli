<?php

namespace App\Commands;

use App\Services\Bucketdesk\Bucketdesk;
use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;
use App\Services\Git\Git;

class ListIssues extends Command {

    protected $signature = 'issues';

    protected $description = 'Command description';

    public function handle()
    {
        $git = new Git();
        $bucketDesk = new Bucketdesk();
//        $this->info($git->currentBranch());
        dd($bucketDesk->issues());
        $this->notify("Hello Web Artisan", "Love beautiful..", "icon.png");
    }
}

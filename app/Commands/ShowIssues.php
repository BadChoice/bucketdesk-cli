<?php

namespace App\Commands;

use App\Services\Bucketdesk\Bucketdesk;
use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;
use App\Services\Git\Git;

class ShowIssues extends Command {

    protected $signature = 'show';

    protected $description = 'Command description';

    public function handle()
    {
//        $git = new Git();
        $bucketDesk = new Bucketdesk();
        $bucketDesk->issues()->each(function($issue){
            $this->info($this->infoDescription($issue));
        });
    }

    private function infoDescription($issue){
        return collect([
            //str_pad($issue->id, 8),
            str_pad($issue->type(), 12),
            str_pad($issue->priority(), 12),
            str_pad($issue->status(), 8),
            str_pad($issue->username, 18),
            "#" . str_pad($issue->issue_id, 12),
            str_pad($issue->repo(), 12),
            $issue->title,
        ])->implode(" ");
    }
}

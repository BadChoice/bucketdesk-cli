<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

class OpenIssue extends Command
{
    protected $signature = 'open {issue?} {repo?}';

    protected $description = 'Command description';

    use IssueCommand;

    public function handle()
    {
        $issue = $this->getIssueFromArguments();
        if (!$issue) { return $this->warn('Issue not found'); }
        exec("open {$issue->link()}");
    }

}

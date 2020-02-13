<?php

namespace App\Commands;

use App\Models\Issue;
use App\Services\Bucketdesk\Bucketdesk;
use App\Services\Git\Git;
use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

class ChangeTo extends Command
{
    protected $signature = 'changeTo {issue}';

    protected $description = 'Command description';


    public function handle()
    {
        $issue = $this->fetchIssue();
        if (!$issue){
            return;
        }
        $issueBranch = $issue->branch();
        $git = new Git();
        if ($git->doesBranchExist($issueBranch)) {
            $git->checkout($issueBranch)->pull();
        } else {
            $this->info("Branch does not exists, creating it from Dev");
            $git->checkout('dev')->pull()->checkout($issueBranch, true);
            $issue->updateStatus(Issue::STATUS_OPEN);
        }
    }

    /**
     * @return Issue
     */
    private function fetchIssue(){
        $git = new Git();
        $issueId = $this->argument("issue");
        $repo = $git->getRepoName();
        return tap( (new Bucketdesk())->issue($repo, $issueId), function($issue) use($issueId, $repo) {
            if (!$issue) $this->error("Issue {$issueId} does not exist at repository {$repo}");
        });
    }


}

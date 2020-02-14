<?php

namespace App\Commands;

use App\Models\Issue;
use LaravelZero\Framework\Commands\Command;

class ChangeTo extends Command
{
    protected $signature = 'changeTo {issue}';

    protected $description = 'Marks the issue as open and creates the branch from dev to start working on it';

    use IssueCommand;

    public function handle()
    {
        $issue = $this->fetchIssue();
        if (! $issue){ return; }
        $issueBranch = $issue->branch();
        if ($this->git->doesBranchExist($issueBranch)) {
            $this->git->checkout($issueBranch)->pull();
        } else {
            $this->info("Branch does not exists, creating it from Dev");
            $this->git->checkout('dev')->pull()->checkout($issueBranch, true);
            $issue->updateStatus(Issue::STATUS_OPEN);
        }
    }

    /**
     * @return Issue
     */
    private function fetchIssue(){
        return tap ($this->getIssueFromArguments(), function($issue) {
            if (! $issue) $this->error("Issue does not exist ");
        });
    }

}

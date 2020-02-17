<?php

namespace App\Commands;

use App\Models\Issue;
use App\Services\Bucketdesk\Bucketdesk;
use LaravelZero\Framework\Commands\Command;

class Finish extends Command
{
    protected $signature = 'finish {issue?}';

    protected $description = 'Creates a pull request for the issue';

    use IssueCommand;

    public function handle()
    {
        //TODO : Fer el ref
        $this->git->push();
        $issue = $this->fetchIssue();
        if ($issue->pull_request) {
            $this->info("Pull request already exists: " . $issue->pull_request);
            return $this->git->checkout('dev');
        }
        //TODO: Posar-hi el #link a la issue
        //TODO: Fer-lo que sigui de qui toca?
        $result = (new Bucketdesk)->createPullRequest($issue);
        $this->info("here is the link: " . $result->link());
        $this->git->checkout('dev');
    }

    /**
     * @return Issue
     */
    private function fetchIssue(){
        return tap ($this->autoFindIssue(), function($issue) {
            if (!$issue) $this->error("Issue not found");
        });
    }
}

<?php

namespace App\Commands;

use App\Models\Issue;
use App\Services\Bucketdesk\Bucketdesk;
use App\Services\Git\Git;
use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

class Finish extends Command
{

    protected $signature = 'finish {issue}';

    protected $description = 'Command description';

    use IssueCommand;

    public function handle()
    {
        $this->git->push();
        $issue = $this->fetchIssue();
        if ($issue->pull_request) {
            $this->info("Pull request already exists: " . $issue->pull_request);
            return $this->git->checkout('dev');
        }
        $result = (new Bucketdesk)->createPullRequest($issue);
        $this->info("here is the link: " . $result->link);
        $this->git->checkout('dev');
    }

    /**
     * @return Issue
     */
    private function fetchIssue(){
        $issueId = $this->argument("issue");
        $repo = $this->git->getRepoName();
        return tap( $this->bucketDesk->issue($repo, $issueId), function($issue) use($issueId, $repo) {
            if (!$issue) $this->error("Issue {$issueId} does not exist at repository {$repo}");
        });
    }
}

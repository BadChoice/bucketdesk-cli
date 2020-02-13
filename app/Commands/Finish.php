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

    public function handle()
    {
        $git = new Git();
        $git->push();
        $issue = $this->fetchIssue();
        if ($issue->pull_request) {
            $this->info("Pull request already exists: " . $issue->pull_request);
            return $git->checkout('dev');
        }
        $result = (new Bucketdesk)->createPullRequest($issue);
        $this->info("here is the link: " . $result->link);
        $git->checkout('dev');
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

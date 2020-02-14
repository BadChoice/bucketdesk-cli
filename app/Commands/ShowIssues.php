<?php

namespace App\Commands;

use App\Services\Bucketdesk\Bucketdesk;
use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;
use App\Services\Git\Git;

class ShowIssues extends Command {

    protected $signature = 'show {issue?} {repo?}';

    protected $description = 'Get a list of issues';

    protected $git;
    protected $bucketDesk;

    public function __construct()
    {
        parent::__construct();
        $this->bucketDesk  = new Bucketdesk();
        $this->git         = new Git();
    }

    public function handle()
    {
        if ($this->argument('issue')) {
            return $this->showIssue($this->argument('issue'));
        }
        $this->bucketDesk->issues()->each(function($issue){
            $this->info($this->infoDescription($issue));
        });
    }

    private function showIssue($issueId){
        $repoName   = $this->argument('repo') ?? $this->git->getRepoName();
        $issue      = $this->bucketDesk->issue($repoName, $issueId);
        if ($issue->attributes == null) {
            $this->warn("Issue not found");
            return;
        }
        $this->info($this->infoDescription($issue));
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

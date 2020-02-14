<?php

namespace App\Commands;

use LaravelZero\Framework\Commands\Command;

class ShowIssues extends Command {

    protected $signature = 'show {issue?} {repo?}';

    protected $description = 'Get a list of issues';

    use IssueCommand;

    public function handle()
    {
        if ($this->argument('issue')) {
            $issue = $this->getIssueFromArguments();
            if (! $issue) { return $this->warn('Issue not found'); }
            return $this->info($this->infoDescription($issue));
        }
        $this->bucketDesk->issues()->each(function($issue){
            $this->info($this->infoDescription($issue));
        });
    }

    private function showIssue($issueId){
        if ($issueId == 'current'){ return $this->showCurrentIssue(); }
        $repoName   = $this->argument('repo') ?? $this->git->getRepoName();
        $issue      = $this->bucketDesk->issue($repoName, $issueId);
        if ($issue->attributes == null) {
            $this->warn("Issue not found");
            return;
        }
        $this->info($this->infoDescription($issue));
    }

    private function showCurrentIssue(){
        $found = $this->autoFindIssue();
        if (! $found->attributes) {
            return $this->warn("Issue not found");
        }
        return $this->info($this->infoDescription($found));
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

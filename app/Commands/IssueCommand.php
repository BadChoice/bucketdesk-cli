<?php
namespace App\Commands;

use App\Services\Bucketdesk\Bucketdesk;
use App\Services\Git\Git;
use Illuminate\Support\Str;

trait IssueCommand {
    protected $git;
    protected $bucketDesk;

    public function __construct()
    {
        parent::__construct();
        $this->bucketDesk  = new Bucketdesk();
        $this->git         = new Git();
    }

    public function autoFindIssue() {
        $repo    = $this->git->getRepoName();
        $branch  = $this->git->currentBranch();
        $issueId = str_replace("feature/issue-", "", $branch);
        return $this->bucketDesk->issue($repo, $issueId);
    }

    protected function getIssueFromArguments(){
        $issueId    = $this->argument("issue");
        if (! $issueId) return null;
        if ($issueId == "current") { return $this->autoFindIssue(); }
        $repoName = $this->argument('repo') ?? $this->git->getRepoName();
        return $this->bucketDesk->issue($repoName, $issueId);
    }
}

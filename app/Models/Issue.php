<?php


namespace App\Models;


use App\Services\Bucketdesk\Bucketdesk;

class Issue
{
    public $attributes;

    const STATUS_NEW = 1;
    const STATUS_OPEN = 2;
    const STATUS_HOLD = 3;
    const STATUS_RESOLVED = 4;
    const STATUS_CLOSED = 5;
    const STATUS_INVALID = 6;
    const STATUS_DUPLICATED = 7;
    const STATUS_WONTFIX = 8;

    public function __construct($attributes)
    {
        $this->attributes = $attributes;
    }

    public function status()
    {
        return [
                1 => 'NEW',
                2 => 'OPEN',
                3 => 'HOLD',
                4 => 'RESOLVED',
                5 => 'CLOSED',
                6 => 'INVALID',
                7 => 'DUPLICATED',
                8 => 'WONTFIX',
            ][$this->status] ?? "?";
    }

    public function priority()
    {
        return [
            1 => 'TRIVIAL',
            2 => 'MINOR',
            3 => 'MAJOR',
            4 => 'CRITICAL',
            5 => 'BLOCKER',
        ][$this->priority] ?? "?";
    }

    public function type()
    {
        return [
            1 => 'TASK',
            2 => 'BUG',
            3 => 'ENHANCEMENT',
            4 => 'PROPOSAL',
        ][$this->type] ?? "?";
    }

    public function repo()
    {
        return $this->repository["name"];
    }

    public function branch(){
        return "feature/issue-{$this->issue_id}";
    }

    public function updateStatus($status){
        return (new Bucketdesk)->updateIssueStatus($this, $status);
    }

    public function createPr(){
        if ($this->pull_request) {
            throw new \Exception("Pull request already created");
        }
        return (new Bucketdesk)->createPullRequest($this);
    }

    public function __get($name)
    {
        return $this->attributes[$name];
    }

    public function link(){
        return "https://bitbucket.org/{$this->repository["account"]}/{$this->repository["repo"]}/issues/{$this->issue_id}";
    }
}

<?php


namespace App\Services\Bucketdesk;


use App\Models\Issue;
use Zttp\Zttp;

class Bucketdesk
{

    public function __construct()
    {
        $this->url       = config('bucketdesk.url') .'/api/';
        $this->api_token = config('bucketdesk.api_token');
    }

    /**
     * @return array<Issue>
     */
    public function issues(){
        $json = Zttp::get($this->url . 'issues')->json();
        return collect($json)->map(function($issue){
            return new Issue($issue);
        });
    }

    /**
     * @param $repo
     * @param $issue
     * @return Issue
     */
    public function issue($repo, $issue){
        $json = Zttp::get($this->url . "issues/$repo/$issue")->json();
        return new Issue($json);
    }

    /**
     * @param $repo
     * @param $issue
     * @param $status
     * @return Issue
     */
    public function updateIssueStatus($repo, $issue, $status)
    {
        $json = Zttp::put($this->url . "issues/$repo/$issue", ["status" => $status])->json();
        return new Issue($json);
    }
}

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

    public function headers(){
        return ["Authorization" => "Bearer {$this->api_token}"];
    }

    public function zttp()
    {
        return Zttp::withHeaders($this->headers());
    }

    /**
     * @param array $params //user, repo, status..
     * @return array<Issue>
     */
    public function issues($params = []){
        $resource = 'issues';
        if ($params){
            $resource .= "?" . collect($params)->map(function($value, $key){
                return "$key=$value";
            })->implode("&");
        }
        $json = $this->zttp()->get($this->url . $resource)->json();
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
        $json = $this->zttp()->get($this->url . "issues/$repo/$issue")->json();
        if ($json == null) return null;
        return new Issue($json);
    }

    /**
     * @param $repo
     * @param $issue
     * @param $status
     * @return Issue
     */
    public function updateIssueStatus(Issue $issue, $status)
    {
        $json = $this->zttp()->put($this->url . "issues/{$issue->repo()}/{$issue->issue_id}", ["status" => $status])->json();
        return new Issue($json);
    }

    public function createPullRequest(Issue $issue){
        return $this->zttp()->post($this->url . "issues/{$issue->repo()}/{$issue->issue_id}/pr")->json();
    }
}

<?php

namespace App\Services\Git;

class Git
{
    public function remote(){
        return $this->execute('git config --get remote.origin.url');
    }

    public function getRepoName()
    {
        return str_replace(".git", "", collect(explode("/", $this->remote()))->last());
    }

    public function currentBranch()
    {
        return $this->execute('git rev-parse --abbrev-ref HEAD');
    }

    public function checkout($branch, $createBranch = false)
    {
        if (! $createBranch) {
            $this->execute("git checkout $branch");
        } else {
            $this->execute("git checkout -b $branch");
        }
        return $this;
    }

    public function pull() {
        $this->execute("git pull");
        return $this;
    }

    public function push(){
        $this->execute("git pull");
        return $this;
    }

    public function nah(){
        return $this->execute('git reset --hard; git clean -df;');
    }

    public function doesBranchExist($branchName){
        exec("git rev-parse --verify {$branchName}", $output, $return);
        return $return == 0;
    }

    public function startFeature($feature){
        return $this->execute("git checkout -b feature/{$feature}");
    }

    private function execute($command){
        return trim(shell_exec($command));
    }
}

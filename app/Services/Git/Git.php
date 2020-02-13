<?php

namespace App\Services\Git;

class Git
{
    public function remote(){
        return $this->execute('git config --get remote.origin.url');
    }

    public function currentBranch()
    {
        return $this->execute('git rev-parse --abbrev-ref HEAD');
    }

    public function nah(){
        return $this->execute('git reset --hard; git clean -df;');
    }

    public function startFeature($feature){
        return $this->execute("git checkout -b feature/{$feature}");
    }

    private function execute($command){
        return trim(shell_exec($command));
    }
}

<?php


namespace App\Services\Bucketdesk;


use Zttp\Zttp;

class Bucketdesk
{

    public function __construct()
    {
        $this->url       = config('bucketdesk.url') .'/api/';
        $this->api_token = config('bucketdesk.api_token');
    }

    public function issues(){
//        dd($this->url . 'issues');
        return Zttp::get($this->url . 'issues')->json();
    }
}

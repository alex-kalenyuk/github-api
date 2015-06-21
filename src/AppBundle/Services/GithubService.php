<?php

namespace AppBundle\Services;

use GuzzleHttp\Client;

class GithubService
{
    public $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function getRepos()
    {
        $response = $this->client->get('users/alex-kalenyuk/repos')->getBody()->getContents();

        return json_decode($response, true);
    }
}

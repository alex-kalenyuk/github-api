<?php

namespace AppBundle\Services;

use GuzzleHttp\Client;

class GithubService
{
    /**
     * @var Client
     */
    public $client;
    

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Get all repositories from my account
     *
     * @param int $page
     * @param int $perPage
     * @return mixed
     */
    public function getRepos($page = 1, $perPage = 10)
    {
        $response = $this->client
            ->get("users/alex-kalenyuk/repos?page=" . $page . "&per_page=" . $perPage)
            ->getBody()
            ->getContents();

        return json_decode($response, true);
    }
}

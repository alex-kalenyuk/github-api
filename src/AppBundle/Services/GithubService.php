<?php

namespace AppBundle\Services;

use GuzzleHttp\Client;

class GithubService
{
    const REPO_TYPE_ALL = 'all';

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
     * @param string $type
     * @return mixed
     */
    public function getRepos($page = 1, $perPage = 10, $type = self::REPO_TYPE_ALL)
    {
        $repoType = in_array($type, ['all', 'owner', 'public', 'private', 'member'])
            ? $type
            : self::REPO_TYPE_ALL;

        $response = $this->client
            ->get("users/alex-kalenyuk/repos?type=" . $repoType . "&page=" . $page . "&per_page=" . $perPage)
            ->getBody()
            ->getContents();

        return json_decode($response, true);
    }
}

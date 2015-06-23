<?php

namespace AppBundle\Tests\Controller\v2;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Client;

class ReposControllerTest extends WebTestCase
{
    /**
     * @var Client
     */
    public $client;

    public function setUp()
    {
        $this->client = static::createClient();
    }

    public function testGetReposAction()
    {
        $this->client->request('GET', '/v2/repos');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertArrayHasKey('repositories', $this->getJsonResponse());
        $this->assertGreaterThan(3, count($this->getJsonResponse()['repositories']));
    }

    public function testGetReposActionEmpty()
    {
        $this->client->request('GET', '/v2/repos?page=1&per_page=10&type=member');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertEquals(['repositories'=>[]], $this->getJsonResponse());
    }

    public function testPostRepoCommentsAction()
    {
        $this->createCommentResponse();
    }

    public function testPutRepoCommentsAction()
    {
        $this->createCommentResponse();
        $createdComment = $this->getJsonResponse()['comment'];

        $updatedComment = $createdComment;
        $updatedComment['comment'] .= '1';

        $this->client->request(
            'PUT',
            '/v2/repos/github-api/comments/' . $createdComment['id'],
            ['comment' => $updatedComment['comment']]
        );

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertEquals(['comment' => $updatedComment], $this->getJsonResponse());

    }


    public function createCommentResponse()
    {
        $this->client->request('POST', '/v2/repos/github-api/comments', ['comment' => 'comment text']);
        $this->assertEquals(201, $this->client->getResponse()->getStatusCode());
        $this->assertArrayHasKey('comment', $this->getJsonResponse());
    }

    public function getJsonResponse()
    {
        return json_decode($this->client->getResponse()->getContent(), true);
    }
}

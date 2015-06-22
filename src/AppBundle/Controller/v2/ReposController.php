<?php

namespace AppBundle\Controller\v2;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Request\ParamFetcherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ReposController extends FOSRestController
{
    /**
     * Get the list of repositories
     * @param ParamFetcher $paramFetcher
     * @return array data
     *
     * @QueryParam(name="page", requirements="\d+", default="1", description="Page of the list.")
     * @QueryParam(name="per_page", requirements="\d+", default="10")
     * @QueryParam(name="type", default="all", description="Type of repo.")
     */
    public function getReposAction(ParamFetcher $paramFetcher)
    {
        $repos = $this->get('github_service')->getRepos(
            $paramFetcher->get('page'),
            $paramFetcher->get('per_page'),
            $paramFetcher->get('type')
        );

        return $this->handleView(
            $this->view($repos, 200)
        );
    }

    /**
     * Get comments of repository
     * @param string $repoName
     * @param ParamFetcher $paramFetcher
     * @return array data
     *
     * @QueryParam(name="page", requirements="\d+", default="1", description="Page of the list.")
     * @QueryParam(name="per_page", requirements="\d+", default="10")
     */
    public function getRepoCommentsAction($repoName, ParamFetcher $paramFetcher)
    {
        $repos = $this->get('github_service')->getCommentsByRepo(
            $repoName,
            $paramFetcher->get('page'),
            $paramFetcher->get('per_page')
        );

        return $this->handleView(
            $this->view([$repos], 200)
        );
    }
}

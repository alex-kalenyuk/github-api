<?php

namespace AppBundle\Controller\v1;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use Symfony\Component\HttpFoundation\Response;

class ReposController extends FOSRestController
{
    /**
     * Get the list of repositories
     * @param ParamFetcher $paramFetcher
     * @return array data
     *
     * @QueryParam(name="page", requirements="\d+", default="1", description="Page of the list.")
     * @QueryParam(name="per_page", requirements="\d+", default="10")
     */
    public function getReposAction(ParamFetcher $paramFetcher)
    {
        $repos = $this->get('github_service')->getRepos(
            $paramFetcher->get('page'),
            $paramFetcher->get('per_page')
        );

        return $this->handleView(
            $this->view($repos, Response::HTTP_OK)
        );
    }
}

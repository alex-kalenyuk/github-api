<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;

class ReposController extends FOSRestController
{
    public function getReposAction()
    {
        $view = $this->view(
            $this->get('github_service')->getRepos(),
            200
        );

        return $this->handleView($view);
    }
}

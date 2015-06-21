<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;

class ReposController extends FOSRestController
{
    public function getReposAction()
    {
        $data = [];
        $view = $this->view($data, 200);

        return $this->handleView($view);
    }
}

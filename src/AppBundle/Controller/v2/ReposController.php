<?php

namespace AppBundle\Controller\v2;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Controller\Annotations\RequestParam;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\View\RouteRedirectView;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\CommentType;
use Symfony\Component\HttpFoundation\Response;

class ReposController extends FOSRestController
{
    /**
     * Get the list of repositories
     *
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
            $this->view(['repositories' => $repos], Response::HTTP_OK)
        );
    }

    /**
     * Get commit comments for a repository
     *
     * @param string $repoName
     * @param ParamFetcher $paramFetcher
     * @return array data
     *
     * @QueryParam(name="page", requirements="\d+", default="1", description="Page of the list.")
     * @QueryParam(name="per_page", requirements="\d+", default="10")
     */
    public function getRepoCommentsAction($repoName, ParamFetcher $paramFetcher)
    {
        $comments = $this->get('comments_service')->getCommentsByRepo(
            $repoName,
            $paramFetcher->get('page'),
            $paramFetcher->get('per_page')
        );

        return $this->handleView(
            $this->view(['comments' => $comments], Response::HTTP_OK)
        );
    }

    /**
     * Get a single commit comment
     *
     * @param string $repoName
     * @param int $id
     * @return array data
     */
    public function getRepoCommentAction($repoName, $id)
    {
        $comment = $this->get('comments_service')
            ->getRepoCommentById($repoName, $id);

        return $this->handleView(
            $this->view(['comment' => $comment], Response::HTTP_OK)
        );
    }

    /**
     * Create comment
     *
     * @param string $repoName
     * @param ParamFetcher $paramFetcher
     * @return array data
     *
     * @RequestParam(name="comment", requirements="\w{2,}")
     */
    public function postRepoCommentsAction($repoName, ParamFetcher $paramFetcher)
    {
        /** @var CommentType */
        $form = $this->createForm('comment');
        $form->submit(['repoName' => $repoName, 'comment' => $paramFetcher->get('comment')]);
        if ($form->isValid()) {
            $comment = $this->get('comments_service')
                ->createComment($form->getData());
            $view = $this->view(['comment' => $comment], Response::HTTP_CREATED);
        } else {
            $errors = $form->getErrors(true);
            $view = $this->view(['errors' =>$form->getErrors()], Response::HTTP_BAD_REQUEST);
        }

        return $this->handleView($view);
    }

    /**
     * Update comment
     *
     * @param string $repoName
     * @param int $id
     * @param ParamFetcher $paramFetcher
     * @return array data
     *
     * @RequestParam(name="comment", requirements="\w{2,}")
     */
    public function putRepoCommentsAction($repoName, $id, ParamFetcher $paramFetcher)
    {
        $comment = $this->get('comments_service')
            ->updateComment($id, $paramFetcher->get('comment'));
        if ($comment) {
            $view = $this->view(['comment' => $comment], Response::HTTP_OK);
        } else {
            $view = $this->view(['error' => 'Comment not found'], Response::HTTP_BAD_REQUEST);
        }

        return $this->handleView($view);
    }
}

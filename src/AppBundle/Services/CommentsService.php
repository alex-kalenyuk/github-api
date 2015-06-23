<?php

namespace AppBundle\Services;

use AppBundle\Entity\Comment;
use Doctrine\ORM\EntityManager;

class CommentsService
{
    /**
     * @var EntityManager
     */
    private $entityManager;


    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Create record
     *
     * @param Comment $comment
     * @return Comment
     */
    public function createComment(Comment $comment)
    {
        $this->entityManager->persist($comment);
        $this->entityManager->flush();

        return $comment;
    }

    /**
     * Update record
     *
     * @param int $id
     * @param string $commentBody
     * @return Comment|bool
     */
    public function updateComment($id, $commentBody)
    {
        $entity = $this->entityManager
            ->getRepository('AppBundle:Comment')
            ->find($id);

        if (!$entity) {
            return false;
        }

        $entity->setComment($commentBody);
        $this->entityManager->flush();

        return $entity;
    }

    /**
     * Get commit comments for a repository
     *
     * @param string $repoName
     * @param int $page
     * @param int $perPage
     * @return mixed
     */
    public function getCommentsByRepo($repoName, $page = 1, $perPage = 10)
    {
        return $this->entityManager->getRepository('AppBundle:Comment')
            ->findBy(['repoName' => $repoName], [], $perPage, $perPage * ($page - 1));
    }

    /**
     * Get a single commit comment
     *
     * @param string $repoName
     * @param int $id
     * @return mixed
     */
    public function getRepoCommentById($repoName, $id)
    {
        return $this->entityManager->getRepository('AppBundle:Comment')
            ->findOneBy(['id' => $id, 'repoName' => $repoName]);
    }
}

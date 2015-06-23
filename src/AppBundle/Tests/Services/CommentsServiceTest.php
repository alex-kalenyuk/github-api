<?php

namespace AppBundle\Tests\Services;

use AppBundle\Services\CommentsService;

class CommentsServiceTest extends \PHPUnit_Framework_TestCase
{
    const COMMENT_ID = 1;
    const COMMENT_TEXT = 'comment text';
    const REPO_NAME = 'github-api';

    public function testCreateComment()
    {
        $comment = $this
            ->getMockBuilder('\AppBundle\Entity\Comment')
            ->disableOriginalConstructor()
            ->getMock();
        $entityManager = $this
            ->getMockBuilder('\Doctrine\ORM\EntityManager')
            ->disableOriginalConstructor()
            ->getMock();
        $entityManager->expects($this->once())
            ->method('persist')
            ->with($this->identicalTo($comment));
        $entityManager->expects($this->once())
            ->method('flush');

        $commentsService = new CommentsService($entityManager);
        $this->assertInstanceOf(
            '\AppBundle\Entity\Comment',
            $commentsService->createComment($comment)
        );
    }

    public function testUpdateComment()
    {
        $comment = $this
            ->getMockBuilder('\AppBundle\Entity\Comment')
            ->disableOriginalConstructor()
            ->getMock();
        $comment->expects($this->once())
            ->method('setComment')
            ->with($this->equalTo(self::COMMENT_TEXT));

        $commentRep = $this
            ->getMockBuilder('\AppBundle\Entity\CommentRepository')
            ->disableOriginalConstructor()
            ->getMock();
        $commentRep->expects($this->once())
            ->method('find')
            ->will($this->returnValue($comment));

        $entityManager = $this
            ->getMockBuilder('\Doctrine\ORM\EntityManager')
            ->disableOriginalConstructor()
            ->getMock();
        $entityManager->expects($this->once())
            ->method('getRepository')
            ->with($this->equalTo('AppBundle:Comment'))
            ->willReturn($commentRep);
        $entityManager->expects($this->once())
            ->method('flush');

        $commentsService = new CommentsService($entityManager);
        $this->assertInstanceOf(
            '\AppBundle\Entity\Comment',
            $commentsService->updateComment(self::COMMENT_ID, self::COMMENT_TEXT)
        );
    }

    public function testGetRepoCommentById()
    {
        $commentRep = $this
            ->getMockBuilder('\AppBundle\Entity\CommentRepository')
            ->disableOriginalConstructor()
            ->getMock();
        $commentRep->expects($this->once())
            ->method('findOneBy')
            ->with($this->equalTo(['id' => self::COMMENT_ID, 'repoName' => self::REPO_NAME]))
            ->willReturn(true);

        $entityManager = $this
            ->getMockBuilder('\Doctrine\ORM\EntityManager')
            ->disableOriginalConstructor()
            ->getMock();
        $entityManager->expects($this->once())
            ->method('getRepository')
            ->with($this->equalTo('AppBundle:Comment'))
            ->willReturn($commentRep);

        $commentsService = new CommentsService($entityManager);
        $commentsService->getRepoCommentById(self::REPO_NAME, self::COMMENT_ID);
    }
}

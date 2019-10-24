<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-09-06
 * Time: 12:17
 */

namespace Enhavo\Bundle\CommentBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Enhavo\Bundle\CommentBundle\Model\ThreadInterface;
use Sylius\Component\Resource\Model\ResourceInterface;

class Thread implements ThreadInterface, ResourceInterface
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var Comment[]
     */
    private $comments;

    /**
     * @var boolean
     */
    private $enable = true;

    /**
     * Thread constructor.
     */
    public function __construct()
    {
        $this->comments = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param Comment $comment
     */
    public function addComment(Comment $comment)
    {
        $comment->setThread($this);
        $this->comments[] = $comment;
    }

    /**
     * @param Comment $comment
     */
    public function removeComment(Comment $comment)
    {
        $comment->setThread(null);
        $this->comments->remove($comment);
    }

    /**
     * @return Comment[]|ArrayCollection
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    /**
     * @return bool
     */
    public function isEnable(): bool
    {
        return $this->enable;
    }

    /**
     * @param bool $enable
     */
    public function setEnable(bool $enable): void
    {
        $this->enable = $enable;
    }
}

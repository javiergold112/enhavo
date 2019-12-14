<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-05-29
 * Time: 18:19
 */

namespace Enhavo\Bundle\NewsletterBundle\Model;

use Doctrine\Common\Collections\Collection;
use Enhavo\Bundle\BlockBundle\Model\NodeInterface;
use Enhavo\Bundle\NewsletterBundle\Entity\Receiver;

interface NewsletterInterface
{
    const STATE_CREATED = 'created';
    const STATE_PREPARED = 'prepared';
    const STATE_SENDING = 'sending';
    const STATE_SENT = 'sent';

    /**
     * Get id
     *
     * @return integer
     */
    public function getId();

    /**
     * Set title
     *
     * @param string $title
     *
     * @return NewsletterInterface
     */
    public function setTitle($title);

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle();

    /**
     * @return string
     */
    public function getSlug();

    /**
     * @param string $slug
     * @return NewsletterInterface
     */
    public function setSlug($slug);

    /**
     * Set subject
     *
     * @param string $subject
     *
     * @return NewsletterInterface
     */
    public function setSubject($subject);

    /**
     * Get subject
     *
     * @return string
     */
    public function getSubject();

    /**
     * @param NodeInterface $content
     * @return void
     */
    public function setContent(NodeInterface $content);

    /**
     * @return NodeInterface
     */
    public function getContent();

    /**
     * @return string|null
     */
    public function getTemplate(): ?string;

    /**
     * @param string|null $template
     */
    public function setTemplate(?string $template);

    /**
     * @return bool
     */
    public function isPrepared();

    /**
     * @return boolean
     */
    public function isSent();

    /**
     * @return string
     */
    public function getState();

    /**
     * @param string $state
     */
    public function setState(string $state);

    /**
     * Add receiver
     *
     * @param Receiver $receiver
     * @return NewsletterInterface
     */
    public function addReceiver(Receiver $receiver);

    /**
     * Remove receiver
     *
     * @param Receiver $receiver
     */
    public function removeReceiver(Receiver $receiver);

    /**
     * Get group
     *
     * @return Collection|Receiver[]
     */
    public function getReceivers();


    /**
     * @return \DateTime
     */
    public function getStartAt(): \DateTime;

    /**
     * @param \DateTime $startAt
     */
    public function setStartAt(\DateTime $startAt): void;

    /**
     * @return \DateTime
     */
    public function getFinishAt(): \DateTime;
    /**
     * @param \DateTime $finishAt
     */
    public function setFinishAt(\DateTime $finishAt): void;
}

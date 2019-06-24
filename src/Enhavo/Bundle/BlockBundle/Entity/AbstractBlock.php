<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 08.08.18
 * Time: 22:26
 */

namespace Enhavo\Bundle\BlockBundle\Entity;

use Enhavo\Bundle\BlockBundle\Model\BlockInterface;
use Enhavo\Bundle\BlockBundle\Model\NodeInterface;

class AbstractBlock implements BlockInterface
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var NodeInterface
     */
    private $node;

    /**
     * @var string
     */
    private $template;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return NodeInterface
     */
    public function getNode()
    {
        return $this->node;
    }

    /**
     * @param NodeInterface $node
     */
    public function setNode(NodeInterface $node = null)
    {
        $this->node = $node;
    }

    /**
     * @return string
     */
    public function getTemplate(): string
    {
        return $this->template;
    }

    /**
     * @param string $template
     */
    public function setTemplate(string $template): void
    {
        $this->template = $template;
    }
}

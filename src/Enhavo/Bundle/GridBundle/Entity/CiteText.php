<?php

namespace Enhavo\Bundle\GridBundle\Entity;

use Enhavo\Bundle\GridBundle\Item\ItemTypeInterface;

/**
 * CiteText
 */
class CiteText implements ItemTypeInterface
{
    /**
     * @var integer
     */
    protected $id;

    protected $text;

    protected $title;

    protected $cite;

    protected $textLeft;

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
     * @return mixed
     */
    public function getTextLeft()
    {
        return $this->textLeft;
    }

    /**
     * @param mixed $textLeft
     */
    public function setTextLeft($textLeft)
    {
        $this->textLeft = $textLeft;
    }

    /**
     * @return mixed
     */
    public function getCite()
    {
        return $this->cite;
    }

    /**
     * @param mixed $cite
     */
    public function setCite($cite)
    {
        $this->cite = $cite;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param mixed $text
     */
    public function setText($text)
    {
        $this->text = $text;
    }
}

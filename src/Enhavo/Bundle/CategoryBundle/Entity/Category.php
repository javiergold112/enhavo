<?php

namespace Enhavo\Bundle\CategoryBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Enhavo\Bundle\CategoryBundle\Model\CategoryInterface;

/**
 * Category
 */
class Category implements CategoryInterface
{

    /**
     * @var integer
     */
    protected $id;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var \Enhavo\Bundle\CategoryBundle\Entity\Collection
     */
    protected $collection;

    /**
     * @var integer
     */
    protected $order;

    /**
     * @var string
     */
    protected $slug;

    /**
     * @var string
     */
    private $text;

    /**
     * @var \Enhavo\Bundle\MediaBundle\Entity\File
     */
    private $picture;

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
     * Set name
     *
     * @param string $name
     * @return Category
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set collection
     *
     * @param \Enhavo\Bundle\CategoryBundle\Entity\Collection $collection
     * @return Category
     */
    public function setCollection(\Enhavo\Bundle\CategoryBundle\Entity\Collection $collection = null)
    {
        $this->collection = $collection;

        return $this;
    }

    /**
     * Get collection
     *
     * @return \Enhavo\Bundle\CategoryBundle\Entity\Collection
     */
    public function getCollection()
    {
        return $this->collection;
    }

    public function __toString()
    {
        if($this->name) {
            return $this->name;
        }
        return '';
    }

    /**
     * Set order
     *
     * @param integer $order
     * @return Category
     */
    public function setOrder($order)
    {
        $this->order = $order;

        return $this;
    }

    /**
     * Get order
     *
     * @return integer
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * Set text
     *
     * @param string $text
     *
     * @return Category
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param \Enhavo\Bundle\MediaBundle\Entity\File|null $picture
     *
     * @return Category
     */
    public function setPicture($picture)
    {
        $this->picture = $picture;

        return $this;
    }

    /**
     * Get picture
     *
     * @return \Enhavo\Bundle\MediaBundle\Entity\File|null
     */
    public function getPicture()
    {
        return $this->picture;
    }

    /**
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }
}

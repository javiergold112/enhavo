<?php

/**
 * ArticleFixture.php
 *
 * @since 27/07/16
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace Enhavo\Bundle\DemoBundle\Fixtures\Fixtures;

use Enhavo\Bundle\ArticleBundle\Entity\Article;
use Enhavo\Bundle\CategoryBundle\Entity\Category;
use Enhavo\Bundle\DemoBundle\Fixtures\AbstractFixture;

class ArticleFixture extends AbstractFixture
{
    /**
     * @inheritdoc
     */
    function create($args)
    {
        $article = new Article();
        $article->setTitle($args['title']);
        $article->setTeaser($args['teaser']);
        $article->setPublic($args['public']);
        $article->setPublicationDate(new \DateTime());
        $article->setPicture($this->createImage($args['picture']));
        $article->setContainer($this->createContainer($args['container']));
        foreach($args['categories'] as $category) {
            $article->addCategory($this->getCategory($category));
        }
        $this->translate($article);
        return $article;
    }

    function getCategory($name)
    {
        return $this->manager->getRepository('EnhavoCategoryBundle:Category')->findOneBy([
            'name' => $name
        ]);
    }

    /**
     * @inheritdoc
     */
    function getName()
    {
        return 'Article';
    }

    /**
     * @inheritdoc
     */
    function getOrder()
    {
        return 20;
    }
}

<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 29.08.18
 * Time: 15:12
 */

namespace Enhavo\Bundle\SearchBundle\Result;


class ResultConfiguration
{
    /**
     * @var string
     */
    private $startTag = '<span class="highlight">';

    /**
     * @var string
     */
    private $closeTag = '</span>';

    /**
     * @var int
     */
    private $length = 160;

    /**
     * @var string
     */
    private $concat = ' ... ';

    /**
     * @var array
     */
    private $guessProperties = null;

    /**
     * @return string
     */
    public function getStartTag()
    {
        return $this->startTag;
    }

    /**
     * @param string $startTag
     */
    public function setStartTag($startTag)
    {
        $this->startTag = $startTag;
    }

    /**
     * @return string
     */
    public function getCloseTag()
    {
        return $this->closeTag;
    }

    /**
     * @param string $closeTag
     */
    public function setCloseTag($closeTag)
    {
        $this->closeTag = $closeTag;
    }

    /**
     * @return int
     */
    public function getLength()
    {
        return $this->length;
    }

    /**
     * @param int $length
     */
    public function setLength($length)
    {
        $this->length = $length;
    }

    /**
     * @return string
     */
    public function getConcat()
    {
        return $this->concat;
    }

    /**
     * @param string $concat
     */
    public function setConcat($concat)
    {
        $this->concat = $concat;
    }

    /**
     * @return array
     */
    public function getGuessProperties()
    {
        return $this->guessProperties;
    }

    /**
     * @param array $guessProperties
     */
    public function setGuessProperties($guessProperties)
    {
        $this->guessProperties = $guessProperties;
    }
}
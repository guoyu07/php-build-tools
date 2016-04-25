<?php

namespace Tremend\BuildTools\Model\Git;

class Tag
{
    /**
     * @var string
     */
    private $hash;

    /**
     * @var string
     */
    private $tag;

    public function __construct($tag, $hash)
    {
        $this->tag = $tag;
        $this->hash = $hash;
    }

    /**
     * @return string
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * @param string $hash
     */
    public function setHash($hash)
    {
        $this->hash = $hash;
    }

    /**
     * @return string
     */
    public function getTag()
    {
        return $this->tag;
    }

    /**
     * @param string $tag
     */
    public function setTag($tag)
    {
        $this->tag = $tag;
    }
}
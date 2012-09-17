<?php

use Doctrine\ODM\PHPCR\Mapping\Annotations as PHPCRODM;

/**
 * @PHPCRODM\Document
 */
class Section
{
    /**
     * @PHPCRODM\Id
     */
    protected $path;

    /**
     * @PHPCRODM\Node
     */
    public $node;

    /**
     * @PHPCRODM\String()
     */
    public $title;

    /**
     * @PHPCRODM\String()
     */
    public $abstract;

    /**
     * @PHPCRODM\String()
     */
    public $sectionText;

    public function getPath()
    {
      return $this->path;
    }
}

<?php

use Doctrine\ODM\PHPCR\DocumentClassMapper;
use Doctrine\ODM\PHPCR\DocumentManager;

use PHPCR\NodeInterface;
use PHPCR\PropertyType;

class MagnoliaDocumentClassMapper extends DocumentClassMapper
{
    private $templateMap;

    /**
     * @param array $templateMap map from mgnl:template values to document class names
     */
    public function __construct($templateMap)
    {
        $this->templateMap = $templateMap;
    }

    /**
     * Determine the class name from a given node
     *
     * @param DocumentManager
     * @param NodeInterface $node
     * @param string $className
     *
     * @return string
     *
     * @throws \RuntimeException if no class name could be determined
     */
    public function getClassName(DocumentManager $dm, NodeInterface $node, $className = null)
    {
        $className = parent::getClassName($dm, $node, $className);
        if ('Doctrine\ODM\PHPCR\Document\Generic' == $className) {
            if ($node->hasNode('MetaData')) {
                $metaData = $node->getNode('MetaData');
                if ($metaData->hasProperty('mgnl:template')) {
                    if (isset($this->templateMap[$metaData->getPropertyValue('mgnl:template')])) {
                        return $this->templateMap[$metaData->getPropertyValue('mgnl:template')];
                    }
                }
            }
        }

        return $className;
    }

    /**
     * Write any relevant meta data into the node to be able to map back to a class name later
     *
     * @param DocumentManager
     * @param NodeInterface $node
     * @param string $className
     */
    public function writeMetadata(DocumentManager $dm, NodeInterface $node, $className)
    {
        if ('Doctrine\ODM\PHPCR\Document\Generic' !== $className) {
            $node->setProperty('phpcr:class', $className, PropertyType::STRING);
        }
    }

    /**
     * @param DocumentManager
     * @param object $document
     * @param string $className
     * @throws \InvalidArgumentException
     */
    public function validateClassName(DocumentManager $dm, $document, $className)
    {
        if (!$document instanceof $className) {
            $class = $dm->getClassMetadata(get_class($document));
            $path = $class->getIdentifierValue($document);
            $msg = "Doctrine metadata mismatch! Requested type '$className' type does not match type '".get_class($document)."' stored in the metadata at path '$path'";
            throw new \InvalidArgumentException($msg);
        }
    }
}

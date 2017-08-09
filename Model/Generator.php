<?php

namespace Sjovanig\CodeGenerator\Model;

use Magento\Framework\Code\Generator\ClassGeneratorFactory;
use Zend\Code\Generator\DocBlock\Tag\GenericTagFactory;
use Zend\Code\Generator\DocBlockGeneratorFactory;

class Generator
{
    /**
     * @var InterfaceGeneratorFactory
     */
    protected $interfaceFactory;

    /**
     * @var ClassGeneratorFactory
     */
    protected $classFactory;

    /**
     * @var DocBlockGeneratorFactory
     */
    protected $docBlockFactory;

    /**
     * @var GenericTagFactory
     */
    protected $genericTagFactory;

    /**
     * @param InterfaceGeneratorFactory $interfaceFactory
     * @param ClassGeneratorFactory $classFactory
     * @param DocBlockGeneratorFactory $docBlockFactory
     * @param GenericTagFactory $genericTagFactory
     */
    public function __construct(
        InterfaceGeneratorFactory $interfaceFactory,
        ClassGeneratorFactory $classFactory,
        DocBlockGeneratorFactory $docBlockFactory,
        GenericTagFactory $genericTagFactory
    )
    {
        $this->interfaceFactory = $interfaceFactory;
        $this->classFactory = $classFactory;
        $this->docBlockFactory = $docBlockFactory;
        $this->genericTagFactory = $genericTagFactory;
    }

    /**
     * @return InterfaceGenerator
     */
    public function createInterface()
    {
        return $this->interfaceFactory->create();
    }

    /**
     * @return \Magento\Framework\Code\Generator\ClassGenerator
     */
    public function createClass()
    {
        return $this->classFactory->create();
    }

    /**
     * @return \Zend\Code\Generator\DocBlockGenerator
     */
    public function createDocBlock()
    {
        return $this->docBlockFactory->create();
    }

    /**
     * @return \Zend\Code\Generator\DocBlock\Tag\GenericTag
     */
    public function createGenericTag()
    {
        return $this->genericTagFactory->create();
    }

    /**
     * @param string $path
     * @param string $content
     */
    public function savePhpFile($path, $content)
    {
        @mkdir(dirname($path), 0755, true);
        if (strpos($content, '<?php') !== 0) {
            $content = "<?php\n\n$content";
        }
        file_put_contents($path, $content);
    }
}
<?php

namespace Sjovanig\CodeGenerator\Model;

use Magento\Framework\App\Area;
use Magento\Framework\App\Filesystem\DirectoryList;

class DiGenerator extends AbstractGenerator
{
    /**
     * @var Generator
     */
    private $generator;
    /**
     * @var DirectoryList
     */
    private $directoryList;
    /**
     * @var null|string
     */
    private $area;
    /**
     * @var string
     */
    private $filePath;
    /**
     * @var ModuleGeneratorFactory
     */
    private $moduleGeneratorFactory;
    /**
     * @var XmlGeneratorFactory
     */
    private $xmlGeneratorFactory;
    /**
     * @var XmlGenerator
     */
    private $xmlGenerator;

    /**
     * @param Context $context
     * @param Generator $generator
     * @param DirectoryList $directoryList
     * @param ModuleGeneratorFactory $moduleGeneratorFactory
     * @param XmlGeneratorFactory $xmlGeneratorFactory
     */
    public function __construct(
        Context $context,
        Generator $generator,
        DirectoryList $directoryList,
        ModuleGeneratorFactory $moduleGeneratorFactory,
        XmlGeneratorFactory $xmlGeneratorFactory
    )
    {
        $this->generator = $generator;
        $this->directoryList = $directoryList;
        $this->moduleGeneratorFactory = $moduleGeneratorFactory;
        $this->xmlGeneratorFactory = $xmlGeneratorFactory;

        parent::__construct($context);
    }

    /**
     * @param null|string $area
     * @return string
     */
    private function getFilePath($area = null)
    {
        $path = $this->context->getDir() . '/etc/';
        if ($area == Area::AREA_ADMINHTML || $area == Area::AREA_FRONTEND) {
            $path .= $area . '/';
        }
        $path .= 'di.xml';
        return $path;
    }

    /**
     * @param null|string $area
     * @return $this
     */
    public function generate($area = null)
    {
        $this->filePath = $this->getFilePath($area);

        $this->moduleGeneratorFactory->create()->generate();

        $this->xmlGenerator = $this->xmlGeneratorFactory->create();
        if (! file_exists($this->filePath)) {
            $doc = $this->xmlGenerator->getDocument();
            $config = $doc->createElement('config');
            $config->setAttribute('xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');
            $config->setAttribute('xsi:noNamespaceSchemaLocation', 'urn:magento:framework:ObjectManager/etc/config.xsd');

            $doc->appendChild($config);
            $this->xmlGenerator->save($this->filePath);
        }
        else {
            $this->xmlGenerator->load($this->filePath);
        }
        return $this;
    }

    /**
     * @return $this
     */
    public function save()
    {
        $this->xmlGenerator->save($this->filePath);
        return $this;
    }

    /**
     * @return XmlGenerator
     */
    public function getXmlGenerator()
    {
        return $this->xmlGenerator;
    }

    /**
     * @return null|string
     */
    public function getArea()
    {
        return $this->area;
    }

    /**
     * @param null|string $area
     * @return DiGenerator
     */
    public function setArea($area)
    {
        $this->area = $area;
        return $this;
    }


}
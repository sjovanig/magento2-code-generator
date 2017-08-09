<?php

namespace Sjovanig\CodeGenerator\Model;

use Magento\Framework\Exception\LocalizedException;

class ModuleGenerator extends AbstractGenerator
{
    const SETUP_VERSION_DEFAULT = '1.0.0';

    /**
     * @var Generator
     */
    private $generator;

    /**
     * @var XmlGeneratorFactory
     */
    private $xmlGeneratorFactory;

    /**
     * @param Context $context
     * @param Generator $generator
     * @param XmlGeneratorFactory $xmlGeneratorFactory
     */
    public function __construct(
        Context $context,
        Generator $generator,
        XmlGeneratorFactory $xmlGeneratorFactory
    )
    {
        $this->generator = $generator;
        $this->xmlGeneratorFactory = $xmlGeneratorFactory;

        parent::__construct($context);
    }

    /**
     * @return $this
     */
    public function generate($setupVersion = self::SETUP_VERSION_DEFAULT)
    {
        if (!$this->validateVersion($setupVersion)) {
            throw new LocalizedException(__('Version "%1" is not valid. Example of valid format: 1.2.3.', $setupVersion));
        }

        $path = $this->context->getDir() . '/registration.php';
        if (! file_exists($path)) {
            $this->generator->savePhpFile(
                $path,
                '\\Magento\\Framework\\Component\\ComponentRegistrar::register(\\Magento\\Framework\\Component\\ComponentRegistrar::MODULE, \''.$this->context->getNamespace().'_'.$this->context->getModule().'\', __DIR__);');
        }

        $path = $this->context->getDir() . '/etc/module.xml';
        if (! file_exists($path)) {
            $xml = $this->xmlGeneratorFactory->create();
            $doc = $xml->getDocument();
            $config = $doc->createElement('config');
            $config->setAttribute('xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');
            $config->setAttribute('xsi:noNamespaceSchemaLocation', 'urn:magento:framework:Module/etc/module.xsd');

            $module = $doc->createElement('module');
            $module->setAttribute('name', $this->context->getNamespace() . '_' . $this->context->getModule());
            $module->setAttribute('setup_version', $setupVersion);

            $config->appendChild($module);

            $doc->appendChild($config);
            $xml->save($path);
        }
        return $this;
    }

    private function validateVersion($version)
    {
        return preg_match('/^[0-9]+\.[0-9]+\.[0-9]+$/', $version);
    }
}
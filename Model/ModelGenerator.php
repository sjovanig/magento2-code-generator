<?php

namespace Sjovanig\CodeGenerator\Model;

use Magento\Framework\Api\SimpleDataObjectConverter;
use Magento\Framework\App\Filesystem\DirectoryList;

class ModelGenerator extends AbstractGenerator
{
    /**
     * @var string
     */
    private $model;

    /**
     * @var Generator
     */
    protected $generator;

    /**
     * @var DirectoryList
     */
    protected $directoryList;
    /**
     * @var PreferenceGeneratorFactory
     */
    private $preferenceGeneratorFactory;

    /**
     * @param PreferenceGeneratorFactory $preferenceGeneratorFactory
     * @param Generator $generator
     * @param DirectoryList $directoryList
     * @param Context $context
     */
    public function __construct(
        PreferenceGeneratorFactory $preferenceGeneratorFactory,
        Generator $generator,
        DirectoryList $directoryList,
        Context $context
    )
    {
        $this->generator = $generator;
        $this->directoryList = $directoryList;
        $this->preferenceGeneratorFactory = $preferenceGeneratorFactory;

        parent::__construct($context);
    }

    /**
     * @param string $model
     */
    public function generate($model)
    {
        $this->model = $model;

        $this->createInterface();
        $this->createModel();
        $this->addPreference();
    }

    private function createInterface()
    {
        $interface = $this->generator->createInterface();
        $interface
            ->setNamespaceName($this->context->getNamespace() . '\\' . $this->context->getModule() . '\\Api\\Data')
            ->setName(SimpleDataObjectConverter::snakeCaseToUpperCamelCase($this->model) . 'Interface')
            ->setExtendedClass('\\Magento\\Framework\\Api\\CustomAttributesDataInterface')
            ->setDocBlock($this->generator->createDocBlock()->setTag(
                $this->generator->createGenericTag()->setName('@api')
            ));

        $this->generator->savePhpFile(
            $this->context->getDir() . '/Api/Data/' . SimpleDataObjectConverter::snakeCaseToUpperCamelCase($this->model) . 'Interface.php',
            $interface->generate()
        );
    }

    private function createModel()
    {
        $class = $this->generator->createClass();
        $class
            ->setNamespaceName($this->context->getNamespace() . '\\' . $this->context->getModule() . '\\Model')
            ->setName(SimpleDataObjectConverter::snakeCaseToUpperCamelCase($this->model))
            ->setExtendedClass('\\Magento\\Framework\\Model\\AbstractExtensibleModel')
            ->setImplementedInterfaces([
                '\\' . $this->context->getNamespace() . '\\' . $this->context->getModule() . '\\Api\\Data\\' . SimpleDataObjectConverter::snakeCaseToUpperCamelCase($this->model) . 'Interface'
            ]);

        $this->generator->savePhpFile(
            $this->context->getDir() . '/Model/' . SimpleDataObjectConverter::snakeCaseToUpperCamelCase($this->model) . '.php',
            $class->generate()
        );
    }

    private function addPreference()
    {
        $preference = $this->preferenceGeneratorFactory->create();
        $preference->generate(
            $this->context->getNamespace() . '\\' . $this->context->getModule() . '\\Api\\Data\\' . SimpleDataObjectConverter::snakeCaseToUpperCamelCase($this->model) . 'Interface',
            $this->context->getNamespace() . '\\' . $this->context->getModule() . '\\Model\\' . SimpleDataObjectConverter::snakeCaseToUpperCamelCase($this->model)
        );
    }
}
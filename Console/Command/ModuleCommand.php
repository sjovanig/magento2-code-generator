<?php

namespace Sjovanig\CodeGenerator\Console\Command;

use SJovanig\CodeGenerator\Model\Context;
use Sjovanig\CodeGenerator\Model\ModuleGeneratorFactory;
use Symfony\Component\Console\Input\InputArgument;

class ModuleCommand extends AbstractCommand
{
    const INPUT_KEY_SETUP_VERSION = 'setup-version';

    /**
     * @var ModuleGeneratorFactory
     */
    private $moduleGeneratorFactory;

    public function __construct(
        Context $context,
        ModuleGeneratorFactory $moduleGeneratorFactory,
        $name = null
    ) {
        parent::__construct($context, $name);
        $this->moduleGeneratorFactory = $moduleGeneratorFactory;
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        parent::configure();
        $this->setName('code-generator:module')
            ->setDescription('Creates module basic files')
            ->addArgument(self::INPUT_KEY_SETUP_VERSION, InputArgument::OPTIONAL, 'Setup version of module. Optional.');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(
        \Symfony\Component\Console\Input\InputInterface $input,
        \Symfony\Component\Console\Output\OutputInterface $output
    ) {
        parent::execute($input, $output);
        $this->moduleGeneratorFactory->create()->generate($input->getArgument(self::INPUT_KEY_SETUP_VERSION));
    }
}

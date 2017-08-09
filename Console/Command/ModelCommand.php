<?php

namespace Sjovanig\CodeGenerator\Console\Command;

use SJovanig\CodeGenerator\Model\Context;
use Sjovanig\CodeGenerator\Model\ModelGeneratorFactory;
use Symfony\Component\Console\Input\InputArgument;

class ModelCommand extends AbstractCommand
{
    const INPUT_KEY_MODEL = 'model';

    /**
     * @var ModelGeneratorFactory
     */
    protected $modelGeneratorFactory;

    /**
     * @param Context $context
     * @param ModelGeneratorFactory $modelGeneratorFactory
     * @param null $name
     */
    public function __construct(
        Context $context,
        ModelGeneratorFactory $modelGeneratorFactory,
        $name = null
    ) {
        $this->modelGeneratorFactory = $modelGeneratorFactory;
        parent::__construct($context, $name);
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        parent::configure();
        $this->setName('code-generator:model')
            ->setDescription('Creates model interface and class')
            ->addArgument(self::INPUT_KEY_MODEL, InputArgument::REQUIRED, 'Model name. Allowed lowercase and underscore.');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(
        \Symfony\Component\Console\Input\InputInterface $input,
        \Symfony\Component\Console\Output\OutputInterface $output
    ) {
        parent::execute($input, $output);
        $this->modelGeneratorFactory->create()->generate($input->getArgument(self::INPUT_KEY_MODEL));
    }
}

<?php

namespace Sjovanig\CodeGenerator\Console\Command;

use SJovanig\CodeGenerator\Model\Context;
use Sjovanig\CodeGenerator\Model\DiGeneratorFactory;
use Symfony\Component\Console\Input\InputArgument;

class DiCommand extends AbstractCommand
{
    const INPUT_KEY_AREA = 'area';
    /**
     * @var DiGeneratorFactory
     */
    private $diGeneratorFactory;

    public function __construct(
        Context $context,
        DiGeneratorFactory $diGeneratorFactory,
        $name = null
    ) {
        parent::__construct($context, $name);
        $this->diGeneratorFactory = $diGeneratorFactory;
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        parent::configure();
        $this->setName('code-generator:di')
            ->setDescription('Creates di.xml file')
            ->addArgument(self::INPUT_KEY_AREA, InputArgument::OPTIONAL, 'Area where create di.xml file. "frontend" or "adminhtml" values are allowed. Optional.');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(
        \Symfony\Component\Console\Input\InputInterface $input,
        \Symfony\Component\Console\Output\OutputInterface $output
    ) {
        parent::execute($input, $output);
        $this->diGeneratorFactory->create()->generate($input->getArgument(self::INPUT_KEY_AREA));
    }
}

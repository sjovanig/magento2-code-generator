<?php

namespace Sjovanig\CodeGenerator\Console\Command;

use SJovanig\CodeGenerator\Model\Context;
use Sjovanig\CodeGenerator\Model\PreferenceGeneratorFactory;
use Symfony\Component\Console\Input\InputArgument;

class PreferenceCommand extends AbstractCommand
{
    const INPUT_KEY_AREA = 'area';

    const INPUT_KEY_FOR = 'for';

    const INPUT_KEY_TYPE = 'type';
    /**
     * @var PreferenceGeneratorFactory
     */
    private $preferenceGeneratorFactory;

    public function __construct(
        Context $context,
        PreferenceGeneratorFactory $preferenceGeneratorFactory,
        $name = null
    ) {
        parent::__construct($context, $name);
        $this->preferenceGeneratorFactory = $preferenceGeneratorFactory;
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        parent::configure();
        $this->setName('code-generator:preference')
            ->setDescription('Creates preference entry in di.xml file')
            ->addArgument(self::INPUT_KEY_FOR, InputArgument::REQUIRED, 'Class to be overridden')
            ->addArgument(self::INPUT_KEY_TYPE, InputArgument::REQUIRED, 'Class that overrides')
            ->addArgument(self::INPUT_KEY_AREA, InputArgument::OPTIONAL, 'Area where override the class. "frontend" or "adminhtml" values are allowed. Optional.');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(
        \Symfony\Component\Console\Input\InputInterface $input,
        \Symfony\Component\Console\Output\OutputInterface $output
    ) {
        parent::execute($input, $output);
        $this->preferenceGeneratorFactory->create()->generate(
            $input->getArgument(self::INPUT_KEY_FOR),
            $input->getArgument(self::INPUT_KEY_TYPE),
            $input->getArgument(self::INPUT_KEY_AREA)
        );
    }
}

<?php

namespace Sjovanig\CodeGenerator\Console\Command;

use SJovanig\CodeGenerator\Model\Context;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class AbstractCommand extends \Symfony\Component\Console\Command\Command
{
    const INPUT_KEY_NAMESPACE = 'namespace';
    const INPUT_KEY_MODULE = 'module';
    const INPUT_KEY_DIR = 'dir';
    const INPUT_KEY_DIR_SHORT = 'd';

    /**
     * @var Context
     */
    protected $context;

    /**
     * @param Context $context
     * @param null $name
     */
    public function __construct(
        Context $context,
        $name = null
    )
    {
        $this->context = $context;

        parent::__construct($name);
    }

    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this->setDefinition([
            new InputArgument(self::INPUT_KEY_NAMESPACE, InputArgument::REQUIRED, 'Module namespace'),
            new InputArgument(self::INPUT_KEY_MODULE, InputArgument::REQUIRED, 'Module name'),
            new InputOption(self::INPUT_KEY_DIR, self::INPUT_KEY_DIR_SHORT, InputOption::VALUE_REQUIRED, 'Directory to generate code. Default: app/code/NAMESPACE/MODULE')
        ]);
    }

    /**
     * @inheritdoc
     */
    protected function execute(
        \Symfony\Component\Console\Input\InputInterface $input,
        \Symfony\Component\Console\Output\OutputInterface $output
    ) {
        $this->context
            ->setNamespace($input->getArgument(self::INPUT_KEY_NAMESPACE))
            ->setModule($input->getArgument(self::INPUT_KEY_MODULE))
            ->setDir($input->getOption(self::INPUT_KEY_DIR));
    }
}
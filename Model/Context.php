<?php

namespace SJovanig\CodeGenerator\Model;

use Magento\Framework\App\Filesystem\DirectoryList;

class Context implements \Magento\Framework\ObjectManager\ContextInterface
{
    /**
     * @var DirectoryList
     */
    private $directoryList;

    /**
     * @param DirectoryList $directoryList
     */
    public function __construct(DirectoryList $directoryList)
    {
        $this->directoryList = $directoryList;
    }

    /**
     * @var string
     */
    protected $namespace;

    /**
     * @var string
     */
    protected $module;

    /**
     * @var string
     */
    protected $dir;

    /**
     * @return string
     */
    public function getNamespace()
    {
        return $this->namespace;
    }

    /**
     * @param string $namespace
     * @return Context
     */
    public function setNamespace($namespace)
    {
        $this->namespace = $namespace;
        return $this;
    }

    /**
     * @return string
     */
    public function getModule()
    {
        return $this->module;
    }

    /**
     * @param string $module
     * @return Context
     */
    public function setModule($module)
    {
        $this->module = $module;
        return $this;
    }

    /**
     * @return string
     */
    public function getDir()
    {
        return $this->dir
            ? rtrim($this->dir, '/')
            : $this->directoryList->getRoot() . '/app/code/' . $this->getNamespace() . '/' . $this->getModule();
    }

    /**
     * @param string $dir
     * @return Context
     */
    public function setDir($dir)
    {
        $this->dir = $dir;
        return $this;
    }
}
<?php

namespace Sjovanig\CodeGenerator\Model;

class XmlGenerator
{
    /**
     * @var \DOMDocument
     */
    private $document;

    public function __construct()
    {
        $this->document = new \DOMDocument('1.0');
        $this->document->preserveWhiteSpace = false;
        $this->document->formatOutput = true;
    }

    /**
     * @return \DOMDocument
     */
    public function getDocument()
    {
        return $this->document;
    }

    /**
     * @param string $path
     * @return $this
     */
    public function load($path)
    {
        $this->document->load($path);
        return $this;
    }

    /**
     * @param string $path
     * @return $this
     */
    public function save($path)
    {
        @mkdir(dirname($path), 0755, true);
        file_put_contents(
            $path,
            preg_replace_callback('/^( +)</m', function($a) {
                return str_repeat(' ',intval(strlen($a[1]) / 2) * 4).'<';
            }, $this->document->saveXML())
        );
        return $this;
    }
}
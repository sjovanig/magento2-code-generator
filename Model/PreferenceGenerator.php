<?php

namespace Sjovanig\CodeGenerator\Model;

use Magento\Framework\Exception\LocalizedException;

class PreferenceGenerator extends AbstractGenerator
{
    /**
     * @var DiGeneratorFactory
     */
    private $diGeneratorFactory;

    /**
     * @param Context $context
     * @param DiGeneratorFactory $diGeneratorFactory
     */
    public function __construct(
        Context $context,
        DiGeneratorFactory $diGeneratorFactory
    )
    {
        $this->diGeneratorFactory = $diGeneratorFactory;

        parent::__construct($context);
    }

    /**
     * @param string $for
     * @param string $type
     */
    public function generate($for, $type, $area = null)
    {
        $di = $this->diGeneratorFactory->create()->generate($area);
        $xml = $di->getXmlGenerator();
        $doc = $xml->getDocument();


        $exists = false;
        /** @var \DOMElement $node */
        foreach ($doc->getElementsByTagName('preference') as $node) {
            if (trim($node->getAttribute('for'), '\\') == trim($for, '\\')) {
                if (trim($node->getAttribute('type'), '\\') != trim($type, '\\')) {
                    throw new LocalizedException(__('Preference for "%1" already exists of type "%2"', $for, $node->getAttribute('type')));
                }
                else {
                    $exists = true;
                }
            }
        }

        if (! $exists) {
            $preference = $doc->createElement('preference');
            $preference->setAttribute('for', $for);
            $preference->setAttribute('type', $type);
            $doc->getElementsByTagName('config')->item(0)->appendChild($preference);
            $di->save();
        }
    }
}
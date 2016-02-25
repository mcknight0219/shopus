<?php
namespace App\Libraries;

class SimpleXMLElementEx extends \SimpleXMLElement
{
    private function addCData($cdataText)
    {
        $node = dom_import_simplexml($this);
        $node->appendChild($node->ownerDocument->createCDATASection($cdataText));
    }

    public function addChildCData($name, $cdataText)
    {
        $child = $this->addChild($name);
        $child->addCData($cdataText);
    }
}
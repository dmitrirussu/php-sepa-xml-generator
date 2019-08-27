<?php

/**
 * Created by Dumitru Russu. e-mail: dmitri.russu@gmail.com
 * Date: 7/8/13
 * Time: 8:46 PM
 * Sepa Xml Generator
 */

namespace SEPA;

use SimpleXMLElement;

require_once 'error_messages.php';

/**
 * Class XMLGenerator
 *
 * @package SEPA
 */
class XMLGenerator extends ValidationRules implements XMLGeneratorInterface
{
    /**
     * XMl File PAIN ISO head line
     */
    const PAIN_008_001_02 = 'pain.008.001.02';
    const PAIN_001_001_02 = 'pain.001.001.02';
    /**
     * SEPA XML document PAIN mode (pain.008.001.02.xsd OR pain.001.001.02.xsd)
     *
     * @var String
     */
    private static $DOCUMENT_PAIN_MODE;
    /**
     * @var
     */
    private $document;
    /**
     * @var array
     */
    private $sepaMessageObjects = array();
    /**
     * @var SimpleXMLElement
     */
    private $xml;

    public function __construct($documentPainMode = self::PAIN_008_001_02)
    {
        $this->setDocumentPainMode($documentPainMode);
        $this->xml = new SimpleXMLElement($this->getDocument());
    }

    public function setDocumentPainMode($documentPainMode)
    {
        self::$DOCUMENT_PAIN_MODE = $documentPainMode;

        $this->document = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
<Document
	xmlns=\"urn:iso:std:iso:20022:tech:xsd:{$documentPainMode}\"
	xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\"
	xsi:schemaLocation=\"urn:iso:std:iso:20022:tech:xsd:{$documentPainMode} {$documentPainMode}.xsd\">
</Document>";

        return $this;
    }

    public function getDocumentPainMode()
    {
        return self::$DOCUMENT_PAIN_MODE;
    }

    public function getDocument()
    {
        return $this->document;
    }

    /**
     * Add Xml Messages
     *
     * @param Message $messageObject
     * @return $this
     */
    public function addXmlMessage(Message $messageObject)
    {
        $this->sepaMessageObjects[] = $messageObject;
        return $this;
    }

    /**
     * Save Xml File
     *
     * @param null $fileName
     * @return bool|mixed
     */
    public function save($fileName = null)
    {
        //save to file
        if ($fileName) {
            $dom = new \DOMDocument('1.0');
            $dom->preserveWhiteSpace = false;
            $dom->formatOutput = true;

            if (!$this->xml->children()) {
                $this->generateMessages();
                $dom->loadXML($this->xml->asXML());
            } else {
                $dom->loadXML($this->getGeneratedXml());
            }

            return ($dom->save($fileName) ? true : false);
        }

        return $this;
    }

    /**
     * Generate Messages
     */
    private function generateMessages()
    {
        /** @var $message Message */
        foreach ($this->sepaMessageObjects as $message) {
            $this->simpleXmlAppend($this->xml, $message->getSimpleXMLElementMessage());
        }
    }

    /**
     * Get Generated Xml
     *
     * @return mixed
     */
    public function getGeneratedXml()
    {
        if (!$this->xml->children()) {
            $this->generateMessages();
        }
        return $this->xml->asXML();
    }

    public function view($withOutOfHeader = false)
    {
        if (!$withOutOfHeader) {
            header("Content-Type:text/xml");
        }

        echo $this->getGeneratedXml();
        return $this;
    }

    /**
     * Simple Xml Append
     *
     * @param $to
     * @param $from
     */
    protected function simpleXmlAppend($to, $from)
    {
        $toDom = dom_import_simplexml($to);
        $fromDom = dom_import_simplexml($from);
        $toDom->appendChild($toDom->ownerDocument->importNode($fromDom, true));
    }

    /**
     * Rename XML Node Name
     *
     * @param SimpleXMLElement $node
     * @param                  $newName
     * @return SimpleXMLElement
     */
    function renameXmlNodeName(SimpleXMLElement $node, $newName)
    {
        $newNode = new SimpleXMLElement("<$newName></$newName>");

        if ($node->childNodes) {
            foreach ($node->childNodes as $child) {
                $newNode->addChild($child);
            }
        }

        if ($node->attributes) {
            foreach ($node->attributes as $attrName => $attrNode) {
                $newNode->addAttribute($attrName, $attrNode);
            }
        }

        return $newNode;
    }
}

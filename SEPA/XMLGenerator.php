<?php

/**
 * Created by Dumitru Russu.
 * Date: 7/8/13
 * Time: 8:46 PM
 * Sepa Xml Generator
 */
namespace SEPA;

	require_once 'error_messages.php';
	require_once './unicode_decode/code/Unidecode.php';
	require_once './iban/iban_validation.func.php';

	/**
	 * Interface XML Generator
	 * Class XMLGeneratorInterface
	 * @package SEPA
	 */
	interface XMLGeneratorInterface {
		public function __construct();
		public function addXmlMessage( Message $message );
		public function getGeneratedXml();
		public function save( $fileName );
		public function view();
		public function __destruct();
	}
	/**
	 * Class XMLGenerator
	 * @package SEPA
	 */
	class XMLGenerator extends  ValidationRules implements XMLGeneratorInterface {

		const INITIAL_HEADLINE = '<?xml version="1.0" encoding="UTF-8"?>
							<Document
								xmlns="urn:iso:std:iso:20022:tech:xsd:pain.008.001.02"
								xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
								xsi:schemaLocation="urn:iso:std:iso:20022:tech:xsd:pain.008.001.02 pain.008.001.02.xsd">
							</Document>';
		/**
		 * @var array
		 */
		private $sepaMessageObjects = array();

		/**
		 * @var \SimpleXMLElement
		 */
		private $xml;

		public function __construct() {

			$this->xml = new \SimpleXMLElement(self::INITIAL_HEADLINE);
		}

		/**
		 * Add Xml Messages
		 * @param Message $messageObject
		 */
		public function addXmlMessage(Message $messageObject) {

			$this->sepaMessageObjects[] = $messageObject;
			return $this;
		}

		/**
		 * Save Xml File
		 * @param null $fileName
		 * @return bool|mixed
		 */
		public function save($fileName = null) {
			//save to file
			if ( $fileName ) {

				$dom = new \DOMDocument('1.0');
				$dom->preserveWhiteSpace = false;
				$dom->formatOutput = true;

				if ( !$this->xml->children() ) {

					$this->generateMessages();
					$dom->loadXML($this->xml->asXML());
				}
				else {

					$dom->loadXML($this->getGeneratedXml());
				}

				return ($dom->save($fileName) ? true : false);
			}

			return $this;
		}

		/**
		 * Generate Messages
		 */
		private function generateMessages() {
			/** @var $message Message */
			foreach ($this->sepaMessageObjects as $message ) {
				$this->simpleXmlAppend($this->xml, $message->getSimpleXMLElementMessage());
			}
		}

		/**
		 * Get Generated Xml
		 * @return mixed
		 */
		public function getGeneratedXml() {
			if ( !$this->xml->children() ) {
				$this->generateMessages();
				return $this->xml->asXML();
			}
			return $this->xml->asXML();
		}

		public function view() {
			header ("Content-Type:text/xml");
			echo $this->getGeneratedXml();
			return $this;
		}

		/**
		 * Simple Xml Append
		 * @param $to
		 * @param $from
		 */
		protected function simpleXmlAppend($to, $from) {
			$toDom = dom_import_simplexml($to);
			$fromDom = dom_import_simplexml($from);
			$toDom->appendChild($toDom->ownerDocument->importNode($fromDom, true));
		}

		public function __destruct() {
			unset($this);
		}
	}

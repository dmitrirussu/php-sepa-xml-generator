<?php
/**
 * Created by Dumitru Russu.
 * Date: 06.10.2013
 * Time: 15:32
 * To change this template use File | Settings | File Templates.
 */

namespace SEPA\Factory;

use SEPA;

require_once 'SEPA/ValidationRules.php';
require_once 'SEPA/XMLGenerator.php';
require_once 'SEPA/Message.php';
require_once 'SEPA/GroupHeader.php';
require_once 'SEPA/PaymentInfo.php';
require_once 'SEPA/DirectDebitTransactions.php';

class XmlGeneratorFactory {

	private function __construct() {

	}

	private function __clone() {

	}

	public static function createXmlGeneratorObject() {
		return new \SEPA\XMLGenerator();
	}

	public static function createXMLMessage(\SEPA\GroupHeader $setGroupHeader = null) {
		$message = new \SEPA\Message();

		//set to Message Group Header
		if ( !empty($setGroupHeader) ) {
			$message->setMessageGroupHeader($setGroupHeader);
		}

		return $message;
	}

	public static function createXMLGroupHeader() {
		return new \SEPA\GroupHeader();
	}

	public static function createXMLPaymentInfo() {
		return new \SEPA\PaymentInfo();
	}

	public static function createXMLDirectDebitTransaction() {
		return new \SEPA\DirectDebitTransaction();
	}
}
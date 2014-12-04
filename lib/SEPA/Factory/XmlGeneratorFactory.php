<?php
/**
 * Created by Dumitru Russu. e-mail: dmitri.russu@gmail.com
 * Date: 06.10.2013
 * Time: 15:32
 * Xml Generator Factory
 */

namespace SEPA\Factory;

use SEPA;

require_once dirname(__FILE__) . '/../ValidationRules.php';
require_once dirname(__FILE__) . '/../XMLGenerator.php';
require_once dirname(__FILE__) . '/../Message.php';
require_once dirname(__FILE__) . '/../GroupHeader.php';
require_once dirname(__FILE__) . '/../PaymentInfo.php';
require_once dirname(__FILE__) . '/../DirectDebitTransactions.php';
require_once dirname(__FILE__) . '/../CreditTransferTransactions.php';

class XmlGeneratorFactory {

	private function __construct() {

	}

	private function __clone() {

	}

	/**
	 * @return SEPA\XMLGenerator
	 */
	public static function createXmlGeneratorObject() {
		return new \SEPA\XMLGenerator();
	}

	/**
	 * Create Xml Message
	 * @param SEPA\GroupHeader $setGroupHeader
	 * @return SEPA\Message
	 */
	public static function createXMLMessage(\SEPA\GroupHeader $setGroupHeader = null) {
		$message = new \SEPA\Message();

		//set to Message Group Header
		if ( !empty($setGroupHeader) ) {
			$message->setMessageGroupHeader($setGroupHeader);
		}

		return $message;
	}

	/**
	 * @return SEPA\GroupHeader
	 */
	public static function createXMLGroupHeader() {
		return new \SEPA\GroupHeader();
	}

	/**
	 * @return SEPA\PaymentInfo
	 */
	public static function createXMLPaymentInfo() {
		return new \SEPA\PaymentInfo();
	}

	/**
	 * @return SEPA\DirectDebitTransaction
	 */
	public static function createXMLDirectDebitTransaction() {
		return new \SEPA\DirectDebitTransaction();
	}

    /**
     * @return SEPA\CreditTransferTransaction
     */
    public static function createXMLCreditTransfertTransaction() {
        return new \SEPA\CreditTransferTransaction();
    }
}
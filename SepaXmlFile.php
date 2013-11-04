<?php
require_once 'SEPA/Factory/XmlGeneratorFactory.php';

use \SEPA\Factory\XmlGeneratorFactory AS SEPAXmlGeneratorFactory;

	/**
	 * Class SepaXmlFile
	 * @package SEPA
	 */
class SepaXmlFile {

	/**
	 * XML Files Repository
	 * @var string
	 */
	public static $_XML_FILES_REPOSITORY = '/xml_files/';

	/**
	 * File Name
	 * @var string
	 */
	public static $_FILE_NAME = 'sepa_test.xml';

	/**
	 * XML & XSD ISO 20022 Files Repository
	 * @var string
	 */
	public static $_ISO_XML_FILES_REPOSITORY = '/ISO20022_archive/';

	/**
	 * ISO 20022 Message ID for Direct Debit Initiation messages
	 * @var string
	 */
	public static $_DIRECT_DEBIT_INITIATION_MSG_ID = 'pain.008.001.02';

	/**
	 * ISO 20022 Message ID for Payment Status Report messages
	 * @var string
	 */
	public static $_PAYMENT_STATUS_REPORT_MSG_ID = 'pain.002.001.03';

	/**
	 * Xml Generator Object
	 * @var SEPA\XMLGenerator
	 */
	private $xmlGeneratorObject;

	/**
	 * @var \SEPA\Message
	 */
	private $messageObject;

	/**
	 * @var \SEPA\PaymentInfo
	 */
	private $paymentInfoObject;

	/**
	 * Advanced Example of Sepa Xml File Messages
	 * @var array
	 */
	public static $_MESSAGES = array(
		array('message_id' => 123,
			'group_header' => array(
				'company_name' => 'Amazing SRL ȘȚțș ыаывпавпва '
			),
			'payment_info' => array(
				'FRST' => array(
					'id' => 1,
					'creditor_iban' => 'MD24 AG00 0225 1000 1310 4168',
					'creditor_bic' => 'AABAFI42',
					'creditor_name' => 'Amazing SRL',
					'scheme_identifier' => 'FR07ZZZ519993',
					'transactions' => array(
						array(
							'id' => 1,
							'endId' => 2,
							'company_name' => 'Roy SRL',
							'amount' => 100.4,
							'umr' => 'SDD000000016PFX0713',
							'iban' => 'FR14 2004 1010 0505 0001 3M02 606',
							'bic' => 'AABAFI22',
							'mandate_sign_date' => '2013-08-03',
							'invoice' => 122
						),
						array(
							'id' => 3,
							'endId' => 3,
							'company_name' => 'Toy SRL',
							'amount' => 10.4,
							'umr' => 'SDD000000016PFX0714',
							'iban' => 'FR14 2004 1010 0505 0001 3M02 606',
							'bic' => 'AABAFI42',
							'mandate_sign_date' => '2013-08-03',
							'invoice' => 1223
						)
					)
				),
				'RCUR' => array(
					'id' => 2,
					'creditor_iban' => 'MD24 AG00 0225 1000 1310 4168',
					'creditor_bic' => 'AABAFI42',
					'creditor_name' => 'Amazing SRL',
					'scheme_identifier' => 'FR07ZZZ519993',
					'transactions' => array(
						array(
							'id' => 4,
							'endId' => 4,
							'company_name' => 'Loy SRL',
							'amount' => 1.4,
							'umr' => 'SDD000000016PFX0715',
							'iban' => 'FR14 2004 1010 0505 0001 3M02 606',
							'bic' => 'AABAFI52',
							'mandate_sign_date' => '2013-08-03',
							'invoice' => 122333
						),
						array(
							'id' => 5,
							'endId' => 7,
							'company_name' => 'Goy SRL',
							'amount' => 100.4,
							'umr' => 'SDD000000016PFX0716',
							'iban' => 'FR14 2004 1010 0505 0001 3M02 606',
							'bic' => 'AABAFI62',
							'mandate_sign_date' => '2013-08-03',
							'invoice' => 122777
						)
					)
				),
				'FNAL' => array(
					'id' => 3,
					'creditor_iban' => 'MD24 AG00 0225 1000 1310 4168',
					'creditor_bic' => 'AABAFI42',
					'creditor_name' => 'Amazing SRL',
					'scheme_identifier' => 'FR07ZZZ519993',
					'transactions' => array(
						array(
							'id' => 8,
							'endId' => 8,
							'company_name' => 'Voy SRL',
							'amount' => 104.0,
							'umr' => 'SDD000000016PFX0717',
							'iban' => 'FR14 2004 1010 0505 0001 3M02 606',
							'bic' => 'AABAFI72',
							'mandate_sign_date' => '2013-08-03',
							'invoice' => 12299988
						),
						array(
							'id' => 9,
							'endId' => 9,
							'company_name' => 'Ioy SRL',
							'amount' => 104.0,
							'umr' => 'SDD000000016PFX0718',
							'iban' => 'FR14 2004 1010 0505 0001 3M02 606',
							'bic' => 'AABAFI82',
							'mandate_sign_date' => '2013-08-03',
							'invoice' => 1229333
						)
					)
				)
			)
		),
		array('message_id' => 124,
			'group_header' => array(
				'company_name' => 'Amazing SRL'
			),
			'payment_info' => array(
				'FRST' => array(
					'id' => 4,
					'creditor_iban' => 'MD24 AG00 0225 1000 1310 4168',
					'creditor_bic' => 'AABAFI42',
					'creditor_name' => 'Amazing SRL',
					'scheme_identifier' => 'FR07ZZZ519993',
					'transactions' => array(
						array(
							'id' => 10,
							'endId' => 10,
							'company_name' => 'Roy SRL',
							'amount' => 100.0,
							'umr' => 'SDD000000016PFX0719',
							'iban' => 'FR14 2004 1010 0505 0001 3M02 606',
							'bic' => 'AABAFI92',
							'mandate_sign_date' => '2013-08-03',
							'invoice' => 122232344
						),
						array(
							'id' => 11,
							'endId' => 12,
							'company_name' => 'Roy SRL',
							'amount' => 100.0,
							'umr' => 'SDD000000016PFX0720',
							'iban' => 'FR14 2004 1010 0505 0001 3M02 606',
							'bic' => 'AABAFI22',
							'mandate_sign_date' => '2013-08-03',
							'invoice' => 1221111
						)
					)
				),
				'RCUR' => array(
					'id' => 5,
					'creditor_iban' => 'MD24 AG00 0225 1000 1310 4168',
					'creditor_bic' => 'AABAFI42',
					'creditor_name' => 'Amazing SRL',
					'scheme_identifier' => 'FR07ZZZ519993',
					'transactions' => array(
						array(
							'id' => 14,
							'endId' => 14,
							'company_name' => 'Roy SRL',
							'amount' => 100.0,
							'umr' => 'SDD000000016PFX0721',
							'iban' => 'FR14 2004 1010 0505 0001 3M02 606',
							'bic' => 'AABAFI22',
							'mandate_sign_date' => '2013-08-03',
							'invoice' => 122454657
						),
						array(
							'id' => 15,
							'endId' => 15,
							'company_name' => 'Roy SRL',
							'amount' => 100.0,
							'umr' => 'SDD000000016PFX0722',
							'iban' => 'FR14 2004 1010 0505 0001 3M02 606',
							'bic' => 'AABAFI22',
							'mandate_sign_date' => '2013-08-03',
							'invoice' => 12224343
						)
					)
				),
				'FNAL' => array(
					'id' => 6,
					'creditor_iban' => 'MD24 AG00 0225 1000 1310 4168',
					'creditor_bic' => 'AABAFI42',
					'creditor_name' => 'Amazing SRL',
					'scheme_identifier' => 'FR07ZZZ519993',
					'transactions' => array(
						array(
							'id' => 16,
							'endId' => 16,
							'company_name' => 'Roy SRL',
							'amount' => 100.0,
							'umr' => 'SDD000000016PFX0723',
							'iban' => 'FR14 2004 1010 0505 0001 3M02 606',
							'bic' => 'AABAFI22',
							'mandate_sign_date' => '2013-08-03',
							'invoice' => 12223435
						),
						array(
							'id' => 17,
							'endId' => 17,
							'company_name' => 'Roy SRL',
							'amount' => 100.0,
							'umr' => 'SDD000000016PFX0724',
							'iban' => 'FR14 2004 1010 0505 0001 3M02 606',
							'bic' => 'AABAFI22',
							'mandate_sign_date' => '2013-08-03',
							'invoice' => 122345456
						)
					)
				)
			)
		)
	);

	public function __construct() {

		$this->xmlGeneratorObject = SEPAXmlGeneratorFactory::createXmlGeneratorObject();
	}

	/**
	 * Export Xml File
	 * @return $this
	 */
	public function export() {

		$this->generatedMessages();

		return $this;
	}

	/**
	 * Generate Messages
	 */
	public function generatedMessages() {
		foreach (self::$_MESSAGES as $message ) {
			/** @var $message \SEPA\Message */
			if ( is_object($message) && $message instanceof \SEPA\GroupHeader ) {

				$this->messageObject = SEPAXmlGeneratorFactory::createXMLMessage(
					$message->getMessageGroupHeader()
				);
			}
			else {

				$message = $this->objectToArray($message);

				$this->messageObject = SEPAXmlGeneratorFactory::createXMLMessage(
					SEPAXmlGeneratorFactory::createXMLGroupHeader()
						->setMessageIdentification($message['message_id'])
						->setInitiatingPartyName($message['group_header']['company_name'])
				);
			}

			if ( is_object($message) && isset($message->payment_info) ) {

				$paymentInfo = 	$message->payment_info;
			}
			elseif( is_object($message) && $message instanceof \SEPA\PaymentInfo ) {

				$paymentInfo = 	$message->getPaymentInfoObjects();
			}
			else {
				$paymentInfo = $message['payment_info'];
			}

			$this->generatePaymentInfo($paymentInfo);

			//add Message To Xml File
			$this->xmlGeneratorObject->addXmlMessage($this->messageObject);
		}
	}

	/**
	 * Generate Payment Info
	 * @param $paymentInfo
	 */
	public function generatePaymentInfo( $paymentInfo ) {

		//set Message Payment Info
		foreach ($paymentInfo as $SequenceType => $paymentInfo ) {

			/** @var $paymentInfo \SEPA\PaymentInfo */
			if (is_object($paymentInfo) && $paymentInfo instanceof \SEPA\PaymentInfo ) {

				$this->paymentInfoObject = $paymentInfo;
			}
			else {
				$paymentInfo = $this->objectToArray($paymentInfo);
				//set payment info
				$this->paymentInfoObject = SEPAXmlGeneratorFactory::createXMLPaymentInfo()
					->setPaymentInformationIdentification($paymentInfo['id'])
					->setSequenceType($SequenceType)
					->setCreditorAccountIBAN($paymentInfo['creditor_iban'])
					->setCreditorAccountBIC($paymentInfo['creditor_bic'])
					->setCreditorName($paymentInfo['creditor_name'])
					->setCreditorSchemeIdentification($paymentInfo['scheme_identifier']);
			}

			if (is_object($paymentInfo) && isset($paymentInfo->transactions) ) {

				$transactions = $paymentInfo->transactions;
			}
			elseif(is_object($paymentInfo) && $paymentInfo instanceof \SEPA\PaymentInfo) {

				$transactions = $paymentInfo->getDirectDebitTransactionObjects();
			}
			else {

				$transactions = $paymentInfo['transactions'];
			}

			//generate Direct Debit Transactions
			$this->generateDirectDebitTransactions($transactions);

			//add Message Payment Info
			$this->messageObject->addMessagePaymentInfo($this->paymentInfoObject);
		}

	}

	/**
	 * Generate Direct Debit Transactions
	 * @param $transactions
	 */
	public function generateDirectDebitTransactions($transactions) {
		foreach ($transactions as $transaction) {

			//add Payment Info transactions
			if ($transaction instanceof \SEPA\DirectDebitTransaction) {

				$this->paymentInfoObject->addDirectDebitTransaction($transaction);
			}
			else {

				$transaction = $this->objectToArray($transaction);

				$this->paymentInfoObject->addDirectDebitTransaction(
					SEPAXmlGeneratorFactory::createXMLDirectDebitTransaction()
						->setInstructionIdentification($transaction['id'])
						->setEndToEndIdentification($transaction['endId'])
						->setInstructedAmount($transaction['amount'])
						->setDebtorName($transaction['company_name'])
						->setDebitIBAN($transaction['iban'])
						->setDebitBIC($transaction['bic'])
						->setMandateIdentification($transaction['umr'])
						->setDateOfSignature($transaction['mandate_sign_date'])
						//->setCurrency('EUR')
						->setDirectDebitInvoice($transaction['invoice'])
				);
			}
		}
	}

	/**
	 * Do Object To array
	 * @param $object
	 * @return array
	 */
	public function objectToArray($object) {
		if ( is_object($object) ) {
			$resultArray = array();
			foreach($object as $key => $value) {
				$result[$key] = $value;
			}
			return $resultArray;
		}
		return $object;
	}

	/**
	 * Save Xml file
	 * @return $this
	 */
	public function save() {
		$fileName = realpath(__DIR__) . static::$_XML_FILES_REPOSITORY. static::$_FILE_NAME;
		$this->xmlGeneratorObject->saveXML( $fileName );
		return $this;
	}

	/**
	 * View Xml File
	 * @return $this
	 */
	public function view() {
		header ("Content-Type:text/xml");
		echo $this->xmlGeneratorObject->getGeneratedXml();
		return $this;
	}

	/**
	 * Validate Direct Debit Transactions Xml File
	 * @param $filepath String path to file	 
	 * @return Boolean
	 */
	public function validateXml($filepath){
		return $this->_validateXml($filepath, realpath(__DIR__) . static::$_ISO_XML_FILES_REPOSITORY . '/' . static::$_DIRECT_DEBIT_INITIATION_MSG_ID . '/' . static::$_DIRECT_DEBIT_INITIATION_MSG_ID . '.xsd' );
	}

	/**
	 * Import Payment Status Report Xml File
	 * @param $filepath String path to file	 
	 * @return $this
	 */
	public function importPaymentStatusReport($filepath){
		// TODO to be implemented
	}

	/**
	 * Validate Payment Status Report Xml File
	 * @param $filepath String path to file	 
	 * @return Boolean
	 */
	public function validatePaymentStatusReportXml($filepath){
		return $this->_validateXml($filepath, realpath(__DIR__) . static::$_ISO_XML_FILES_REPOSITORY . '/' . static::$_PAYMENT_STATUS_REPORT_MSG_ID . '/' . static::$_PAYMENT_STATUS_REPORT_MSG_ID . '.xsd' );
	}	

	/**
	 * Validate SEPA Xml File
	 * @param $filepath String path to file	 
	 * @param $filepath String path to XSD file	 
	 * @return Boolean
	 */
	private function _validateXml($filepath, $xsd_filepath ){
		$xml= new DOMDocument();
		$f=file_get_contents($filepath);
		$xml->loadXML($f, LIBXML_NOBLANKS);
		return $xml->schemaValidate($xsd_filepath);
	}

}
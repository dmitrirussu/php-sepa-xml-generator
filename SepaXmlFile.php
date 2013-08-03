<?php
/**
 * Created by Dumitru Russu.
 * Date: 7/8/13
 * Time: 8:46 PM
 * To change this template use File | Settings | File Templates.
 */
use SEPA\XMLGenerator;
use SEPA\Message;
use SEPA\GroupHeader;
use SEPA\PaymentInfo;
use SEPA\DirectDebitTransactions;
	/**
	 * Class SepaXmlFile
	 * @package SEPA
	 */
class SepaXmlFile {

	/**
	 * XML Files Repository
	 * @var string
	 */
	public static $_XML_FILES_REPOSITORY = '/sepa/xml_files/';

	/**
	 * File Name
	 * @var string
	 */
	public static $_FILE_NAME = 'sepa_test.xml';

	/**
	 * Xml Generator Object
	 * @var SEPA\XMLGenerator
	 */
	private $xmlGeneratorObject;

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
		$this->xmlGeneratorObject = new XMLGenerator();
	}

	/**
	 * Export Xml File
	 * @return $this
	 */
	public function export() {

		foreach (self::$_MESSAGES as $_message ) {
			$message = new Message();

			//set Message Group header Info
			$groupHeader = new GroupHeader();

			$groupHeader->setMessageIdentification($_message['message_id']);
			$groupHeader->setInitiatingPartyName($_message['group_header']['company_name']);

			//set Message group header
			$message->setMessageGroupHeader($groupHeader);

			//set Message Payment Info

			foreach ($_message['payment_info'] as $SequenceType => $_paymentInfo ) {

				//set payment info
				$paymentInfo = new PaymentInfo();
				$paymentInfo->setPaymentInformationIdentification($_paymentInfo['id']);
				$paymentInfo->setSequenceType($SequenceType);
				$paymentInfo->setCreditorAccountIBAN($_paymentInfo['creditor_iban']);
				$paymentInfo->setCreditorAccountBIC($_paymentInfo['creditor_bic']);
				$paymentInfo->setCreditorName($_paymentInfo['creditor_name']);
				$paymentInfo->setCreditorSchemeIdentification($_paymentInfo['scheme_identifier']);

				foreach ($_paymentInfo['transactions'] as $_transaction) {

					//set payment info transactions
					$transaction = new DirectDebitTransactions();
					$transaction->setInstructionIdentification($_transaction['id']);
					$transaction->setEndToEndIdentification($_transaction['endId']);
					$transaction->setInstructedAmount($_transaction['amount']);
					$transaction->setDebtorName($_transaction['company_name']);
					$transaction->setDebitIBAN($_transaction['iban']);
					$transaction->setDebitBIC($_transaction['bic']);
					$transaction->setMandateIdentification($_transaction['umr']);
					$transaction->setDateOfSignature($_transaction['mandate_sign_date']);
//					$transaction->setCurrency('EUR');
					$transaction->setDirectDebitInvoice($_transaction['invoice']);
					
					//add Payment Info transactions
					$paymentInfo->addDirectDebitTransaction($transaction);
				}

				//add Message Payment Info
				$message->addMessagePaymentInfo($paymentInfo);
			}


			//add Message To Xml File
			$this->xmlGeneratorObject->addXmlMessage($message);
		}

		$fileName = dirname(__DIR__) . static::$_XML_FILES_REPOSITORY. static::$_FILE_NAME;
		$this->xmlGeneratorObject->saveXML( $fileName );

		return $this;
	}

	/**
	 * View Xml File
	 * @return mixed
	 */
	public function viewXmlFile() {
		header ("Content-Type:text/xml");
		echo $this->xmlGeneratorObject->getGeneratedXml();
	}
}

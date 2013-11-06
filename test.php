<?php
require_once 'SepaXmlFile.php';
require_once 'SEPA/Factory/XmlGeneratorFactory.php';

$SEPAXml = new SEPAXmlFile();

$SEPAXml::$_XML_FILES_REPOSITORY = realpath(__DIR__) . '/xml_files/';
$SEPAXml::$_FILE_NAME = 'sepa_test.xml';

//Simple Example of Sepa Xml File Messages
$SEPAXml::$_MESSAGES = array(
	array('message_id' => 1,
		'group_header' => array(
			'company_name' => 'Amazing SRL ȘȚțș ыаывпавпва '
		),
		'payment_info' => array(
			'FRST' => array(
				'id' => 6222,
				'creditor_iban' => 'MD24 AG00 0225 1000 1310 4168',
				'creditor_bic' => 'AABAFI42',
				'creditor_name' => 'Amazing SRL',
				'scheme_identifier' => 'FR07ZZZ519993',
				'requested_collection_date' => '2013-08-06',
				'transactions' => array(
					SEPA\Factory\XmlGeneratorFactory::createXMLDirectDebitTransaction()
						->setInstructionIdentification(3)
						->setEndToEndIdentification(39)
						->setInstructedAmount(100.5)
						->setDebtorName('Roy SRL')
						->setDebitIBAN('FR14 2004 1010 0505 0001 3M02 606')
						->setDebitBIC('AABAFI22')
						->setMandateIdentification('SDD000000016PFX0713')
						->setDateOfSignature('2013-08-03')
						//->setCurrency('EUR')
						->setDirectDebitInvoice(122),
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
			))));

//Sepa Export View
//	$SEPAXml->export()->view();

//Sepa Export Save
//	$SEPAXml->export()->save();

//Sepa Export Save and View
//	$SEPAXml->export()->save()->view();

//Seepa Export View and Save
$SEPAXml->export()->view()->save();

// SEPA Xml export validation with ISO20022
//$SEPAXml->export()->validation('pain.008.001.02');

//SEPA xml export convert to array
//$SEPAXml->export()->convertToArray();


<?php

/**
 * Created by Dumitru Russu.
 * Date: 27.01.2014
 * Time: 20:51
 * SEPA\XMLGeneratorFactory
 */

class XMLGeneratorFactoryTest extends PHPUnit_Framework_TestCase {

	public function testCreateXmlFile() {

		$xmlFile = SEPA\Factory\XMLGeneratorFactory::createXmlGeneratorObject()->addXmlMessage(
			SEPA\Factory\XMLGeneratorFactory::createXMLMessage()->setMessageGroupHeader(
				SEPA\Factory\XMLGeneratorFactory::createXMLGroupHeader()
					->setMessageIdentification(1)
					->setInitiatingPartyName('Amazing SRL ???? ыаывпавпва'))
				->addMessagePaymentInfo(
					SEPA\Factory\XMLGeneratorFactory::createXMLPaymentInfo()
						->setPaymentInformationIdentification(6222)
						->setSequenceType('FRST')
						->setCreditorAccountIBAN('MD24 AG00 0225 1000 1310 4168')
						->setCreditorAccountBIC('AABAFI42')->setCreditorName('Amazing SRL')
						->setCreditorSchemeIdentification('FR07ZZZ519993')
						->setRequestedCollectionDate('2013-08-06')
						->addDirectDebitTransaction( //First transaction
							SEPA\Factory\XmlGeneratorFactory::createXMLDirectDebitTransaction()
								->setInstructionIdentification(3)
								->setEndToEndIdentification(3)
								->setInstructedAmount(100.5)
								->setDebtorName('Roy SRL')
								->setDebitIBAN('FR14 2004 1010 0505 0001 3M02 606')
								->setDebitBIC('AABAFI22')
								->setMandateIdentification('SDD000000016PFX0713') //unique Identifier
								->setDateOfSignature('2013-08-03')
//						->setCurrency('EUR')
								->setDirectDebitInvoice(122)
						)
				->addDirectDebitTransaction( //Second transaction are the same client transaction
					SEPA\Factory\XmlGeneratorFactory::createXMLDirectDebitTransaction()
						->setInstructionIdentification(4)
						->setEndToEndIdentification(4)
						->setInstructedAmount(100.5)
						->setDebtorName('Roy SRL')
						->setDebitIBAN('FR14 2004 1010 0505 0001 3M02 606')
						->setDebitBIC('AABAFI22')
						->setMandateIdentification('SDD000000016PFX0713') //unique Identifier
						->setDateOfSignature('2013-08-03')
//						->setCurrency('EUR')
						->setDirectDebitInvoice(122))
				->addDirectDebitTransaction( //An other client Transaction
					SEPA\Factory\XmlGeneratorFactory::createXMLDirectDebitTransaction()
						->setInstructionIdentification(6)
						->setEndToEndIdentification(6)
						->setInstructedAmount(100.5)
						->setDebtorName('ND SRL')
						->setDebitIBAN('FR14 2004 1010 0505 0001 3M02 606')
						->setDebitBIC('AABAFI22')
						->setMandateIdentification('SDD000000016PFX0714') //unique Identifier
						->setDateOfSignature('2013-08-03')
//						->setCurrency('EUR')
						->setDirectDebitInvoice(122))
			)
		)->save()->getGeneratedXml();

		$this->assertNotEmpty($xmlFile);
	}

	public function testSaveGeneratedXMLFile() {
		$xmlFile = SEPA\Factory\XMLGeneratorFactory::createXmlGeneratorObject()->addXmlMessage(
			SEPA\Factory\XMLGeneratorFactory::createXMLMessage()->setMessageGroupHeader(
				SEPA\Factory\XMLGeneratorFactory::createXMLGroupHeader()
					->setMessageIdentification(1)
					->setInitiatingPartyName('Amazing SRL ???? ыаывпавпва'))
				->addMessagePaymentInfo(
					SEPA\Factory\XMLGeneratorFactory::createXMLPaymentInfo()
						->setPaymentInformationIdentification(6222)
						->setSequenceType('FRST')
						->setCreditorAccountIBAN('MD24 AG00 0225 1000 1310 4168')
						->setCreditorAccountBIC('AABAFI42')->setCreditorName('Amazing SRL')
						->setCreditorSchemeIdentification('FR07ZZZ519993')
						->setRequestedCollectionDate('2013-08-06')
						->addDirectDebitTransaction( //First transaction
							SEPA\Factory\XmlGeneratorFactory::createXMLDirectDebitTransaction()
								->setInstructionIdentification(3)
								->setEndToEndIdentification(3)
								->setInstructedAmount(100.5)
								->setDebtorName('Roy SRL')
								->setDebitIBAN('FR14 2004 1010 0505 0001 3M02 606')
								->setDebitBIC('AABAFI22')
								->setMandateIdentification('SDD000000016PFX0713') //unique Identifier
								->setDateOfSignature('2013-08-03')
//						->setCurrency('EUR')
								->setDirectDebitInvoice(122)
						)
						->addDirectDebitTransaction( //Second transaction are the same client transaction
							SEPA\Factory\XmlGeneratorFactory::createXMLDirectDebitTransaction()
								->setInstructionIdentification(4)
								->setEndToEndIdentification(4)
								->setInstructedAmount(100.5)
								->setDebtorName('Roy SRL')
								->setDebitIBAN('FR14 2004 1010 0505 0001 3M02 606')
								->setDebitBIC('AABAFI22')
								->setMandateIdentification('SDD000000016PFX0713') //unique Identifier
								->setDateOfSignature('2013-08-03')
//						->setCurrency('EUR')
								->setDirectDebitInvoice(122))
						->addDirectDebitTransaction( //An other client Transaction
							SEPA\Factory\XmlGeneratorFactory::createXMLDirectDebitTransaction()
								->setInstructionIdentification(6)
								->setEndToEndIdentification(6)
								->setInstructedAmount(100.5)
								->setDebtorName('ND SRL')
								->setDebitIBAN('FR14 2004 1010 0505 0001 3M02 606')
								->setDebitBIC('AABAFI22')
								->setMandateIdentification('SDD000000016PFX0714') //unique Identifier
								->setDateOfSignature('2013-08-03')
//						->setCurrency('EUR')
								->setDirectDebitInvoice(122))
				)
		)->view(true)->save($fileExist = realpath(__DIR__) . '/xml_files/sepa_test.xml');
		$this->assertTrue(file_exists($fileExist));
	}

	public function testCreateTransaction() {

		$transactionSimpleXml = SEPA\Factory\XmlGeneratorFactory::createXMLDirectDebitTransaction()
			->setInstructionIdentification(6)
			->setEndToEndIdentification(6)
			->setInstructedAmount(100.5)
			->setDebtorName('ND SRL')
			->setDebitIBAN('FR14 2004 1010 0505 0001 3M02 606')
			->setDebitBIC('AABAFI22')
			->setMandateIdentification('SDD000000016PFX0714') //unique Identifier
			->setDateOfSignature('2013-08-03')
//		    ->setCurrency('EUR')
			->setDirectDebitInvoice(122)->getSimpleXMLElementPaymentInfo();

		$this->assertNotEmpty($transactionSimpleXml);

	}
}


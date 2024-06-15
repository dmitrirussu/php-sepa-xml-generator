[![Build Status](https://travis-ci.org/dmitrirussu/php-sepa-xml-generator.svg?branch=master)](https://travis-ci.org/dmitrirussu/php-sepa-xml-generator)
    
PHP SEPA XML Generator v 1.0.8
====
Now in this release you are able todo SEPA CreditTransfer and DirDebit

Support: pain.008.001.02, pain.001.001.02

Guide ISO20022 SDD  V1_0 20122009

SEPA Direct Debit Core Scheme (SDD Core) 
====

Single Euro Payments Area (SEPA)

The Single Euro Payments Area (SEPA) is a payment-integration initiative of the European Union for simplification of bank
transfers denominated in euro. As of March 2012, SEPA consists of the 28 EU member states, the four members of the EFTA
(Iceland, Liechtenstein, Norway and Switzerland) and Monaco.

Example of using
===

SEPA Direct Debit
===

```php
		//When you start to generate a SEPA Xml File, need to choose PAIN
		$directDebitTransaction = \SEPA\XMLGenerator::PAIN_008_001_02; // For Direct Debit transactions is By Defaut
		$creditTransfer = \SEPA\XMLGenerator::PAIN_001_001_02; //For Credit Transfer

		SEPA\Factory\XMLGeneratorFactory::createXmlGeneratorObject($directDebitTransaction)->addXmlMessage(
        	SEPA\Factory\XMLGeneratorFactory::createXMLMessage()
        		->setMessageGroupHeader(
        			SEPA\Factory\XMLGeneratorFactory::createXMLGroupHeader()
        				->setMessageIdentification(1)
        				->setInitiatingPartyName('Amazing SRL ???? ыаывпавпва '))
        		->addMessagePaymentInfo(
        			SEPA\Factory\XMLGeneratorFactory::createXMLPaymentInfo()
        				->setPaymentInformationIdentification(6222)
        				->setSequenceType('FRST')
        				->setCreditorAccountIBAN('MD24 AG00 0225 1000 1310 4168')
        				->setCreditorAccountBIC('AABAFI42')->setCreditorName('Amazing SRL')
        				->setCreditorSchemeIdentification('FR07ZZZ519993')
        				->setRequestedCollectionDate('2013-08-06')
						->setAggregatePerMandate(true) //Default Transaction aggregation option = true
        				->addDirectDebitTransaction( //First transaction
        					SEPA\Factory\XmlGeneratorFactory::createXMLDirectDebitTransaction()
        						->setInstructionIdentification(3)
        						->setEndToEndIdentification(3)
        						->setInstructedAmount(100.5)
        						->setDebtorName('Roy SRL')
        						->setDebitIBAN('FR14 2004 1010 0505 0001 3M02 606')
        						->setDebitBIC('AABAFI22') //Optional
        						->setMandateIdentification('SDD000000016PFX0713') //unique Identifier
        						->setDateOfSignature('2013-08-03')
        //						->setCurrency('EUR')
        						->setDirectDebitInvoice(122)
        				)->addDirectDebitTransaction( //Second transaction are the same client transaction
        					SEPA\Factory\XmlGeneratorFactory::createXMLDirectDebitTransaction()
        						->setInstructionIdentification(4)
        						->setEndToEndIdentification(4)
        						->setInstructedAmount(100.5)
        						->setDebtorName('Roy SRL')
        						->setDebitIBAN('FR14 2004 1010 0505 0001 3M02 606')
        						->setDebitBIC('AABAFI22') //Optional
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
        						->setDebitBIC('AABAFI22') //Optional
        						->setMandateIdentification('SDD000000016PFX0714') //unique Identifier
        						->setDateOfSignature('2013-08-03')
        //						->setCurrency('EUR')
        						->setDirectDebitInvoice(122))
        		)

        )->view()->save(realpath(__DIR__) . '/xml_files/sepa_test.xml');

```

SEPA Credit Transfer
===

```php
	$xmlFile = SEPA\Factory\XMLGeneratorFactory::createXmlGeneratorObject(\SEPA\XMLGenerator::PAIN_001_001_02)->addXmlMessage(
			SEPA\Factory\XMLGeneratorFactory::createXMLMessage()->setMessageGroupHeader(
				SEPA\Factory\XMLGeneratorFactory::createXMLGroupHeader()
					->setMessageIdentification(1)
					->setInitiatingPartyName('Amazing SRL ???? ыаывпавпва'))
				->addMessagePaymentInfo(
					SEPA\Factory\XMLGeneratorFactory::createXMLPaymentInfo()
						->setAggregatePerMandate(false)
						->setPaymentInformationIdentification(6222)
						->setSequenceType('FRST')
						->setDebitorAccountIBAN('MD24 AG00 0225 1000 1310 4168')
						->setDebitorAccountBIC('AABAFI42')
						->setDebitorName('Amazing SRL')
						->setRequestedCollectionDate('2013-08-06')
						->addCreditTransferTransaction(
							SEPA\Factory\XmlGeneratorFactory::createXMLCreditTransferTransaction()
								->setInstructionIdentification(3)
								->setCreditInvoice(1223)
								->setInstructedAmount(100.5)
								->setBIC('AABAFI42')
								->setCreditorName('1222')
								->setIBAN('MD24 AG000225100013104168')
						)
						->addCreditTransferTransaction(
							SEPA\Factory\XmlGeneratorFactory::createXMLCreditTransferTransaction()
								->setInstructionIdentification(4)
								->setCreditInvoice(1223)
								->setInstructedAmount(50.5)
								->setBIC('AABAFI42')
								->setCreditorName('1222')
								->setIBAN('MD24 AG000225100013104168')
						)
						->addCreditTransferTransaction(
							SEPA\Factory\XmlGeneratorFactory::createXMLCreditTransferTransaction()
								->setInstructionIdentification(4)
								->setCreditInvoice(1223)
								->setInstructedAmount(25.5)
								->setBIC('AABAFI42')
								->setCreditorName('1222')
								->setIBAN('MD24 AG000225100013104168')
						)
			)
		)->save($fileExist = realpath(__DIR__) . '/xml_files/sepa_test.xml');

		$this->assertTrue(file_exists($fileExist));
```

XML File Result for SEPA Direct Debit
===
```xml
        <Document xmlns="urn:iso:std:iso:20022:tech:xsd:pain.008.001.02" 
                  xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" 
                  xsi:schemaLocation="urn:iso:std:iso:20022:tech:xsd:pain.008.001.02 pain.008.001.02.xsd">
          <CstmrDrctDbtInitn>
            <GrpHdr>
              <MsgId>123</MsgId>
              <CreDtTm>2013-08-03T10:30:02</CreDtTm>
              <NbOfTxs>2</NbOfTxs>
              <CtrlSum>110.80</CtrlSum>
              <InitgPty>
                <Nm>Amazing SRL STts yayvpavpva </Nm>
              </InitgPty>
            </GrpHdr>
            <PmtInf>
              <PmtInfId>1</PmtInfId>
              <PmtMtd>DD</PmtMtd>
              <BtchBookg>false</BtchBookg>
              <NbOfTxs>2</NbOfTxs>
              <CtrlSum>110.80</CtrlSum>
              <PmtTpInf>
                <SvcLvl>
                  <Cd>SEPA</Cd>
                </SvcLvl>
                <LclInstrm>
                  <Cd>CORE</Cd>
                </LclInstrm>
                <SeqTp>FRST</SeqTp>
              </PmtTpInf>
              <ReqdColltnDt>2013-08-03</ReqdColltnDt>
              <Cdtr>
                <Nm>Amazing SRL</Nm>
              </Cdtr>
              <CdtrAcct>
                <Id>
                  <IBAN>MD24AG000225100013104168</IBAN>
                </Id>
              </CdtrAcct>
              <CdtrAgt>
                <FinInstnId>
                  <BIC>AABAFI42</BIC>
                </FinInstnId>
              </CdtrAgt>
              <ChrgBr>SLEV</ChrgBr>
              <CdtrSchmeId>
                <Id>
                  <PrvtId>
                    <Othr>
                      <Id>FR07ZZZ519993</Id>
                      <SchmeNm>
                        <Prtry>SEPA</Prtry>
                      </SchmeNm>
                    </Othr>
                  </PrvtId>
                </Id>
              </CdtrSchmeId>
              <DrctDbtTxInf>
                <PmtId>
                  <InstrId>1</InstrId>
                  <EndToEndId>2</EndToEndId>
                </PmtId>
                <InstdAmt Ccy="EUR">100.40</InstdAmt>
                <DrctDbtTx>
                  <MndtRltdInf>
                    <MndtId>SDD000000016PFX0713</MndtId>
                    <DtOfSgntr>2013-08-03</DtOfSgntr>
                  </MndtRltdInf>
                </DrctDbtTx>
                <DbtrAgt>
                  <FinInstnId>
                    <BIC>AABAFI22</BIC>
                  </FinInstnId>
                </DbtrAgt>
                <Dbtr>
                  <Nm>Roy SRL</Nm>
                </Dbtr>
                <DbtrAcct>
                  <Id>
                    <IBAN>FR1420041010050500013M02606</IBAN>
                  </Id>
                </DbtrAcct>
                <RmtInf>
                  <Ustrd>122</Ustrd>
                </RmtInf>
              </DrctDbtTxInf>
              <DrctDbtTxInf>
                <PmtId>
                  <InstrId>3</InstrId>
                  <EndToEndId>3</EndToEndId>
                </PmtId>
                <InstdAmt Ccy="EUR">10.40</InstdAmt>
                <DrctDbtTx>
                  <MndtRltdInf>
                    <MndtId>SDD000000016PFX0714</MndtId>
                    <DtOfSgntr>2013-08-03</DtOfSgntr>
                  </MndtRltdInf>
                </DrctDbtTx>
                <DbtrAgt>
                  <FinInstnId>
                    <BIC>AABAFI42</BIC>
                  </FinInstnId>
                </DbtrAgt>
                <Dbtr>
                  <Nm>Toy SRL</Nm>
                </Dbtr>
                <DbtrAcct>
                  <Id>
                    <IBAN>FR1420041010050500013M02606</IBAN>
                  </Id>
                </DbtrAcct>
                <RmtInf>
                  <Ustrd>1223</Ustrd>
                </RmtInf>
              </DrctDbtTxInf>
            </PmtInf>
          </CstmrDrctDbtInitn>
        </Document>
```



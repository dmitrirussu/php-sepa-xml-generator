PHP SEPA XML Generator v.0.5
====

In this new release was added two new methods SEPA Xml schema Validation and XML convertion to Array

Guide ISO20022 SDD  V1_0 20122009

SEPA Direct Debit Core Scheme (SDD Core) 
====

Single Euro Payments Area (SEPA)

The Single Euro Payments Area (SEPA) is a payment-integration initiative of the European Union for simplification of bank
transfers denominated in euro. As of March 2012, SEPA consists of the 28 EU member states, the four members of the EFTA
(Iceland, Liechtenstein, Norway and Switzerland) and Monaco.

Example of using.
====
```php
        $SEPA = new SepaXmlFile();

        $SEPA::$_XML_FILES_REPOSITORY = '/sepa/xml_files/';
        $SEPA::$_FILE_NAME = 'sepa_test.xml';

        //Simple Example of Sepa Xml File Messages
        $SEPA::$_MESSAGES = array(
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
						->setDirectDebitInvoice(122)
                        )
                    ))));

            //Sepa Export View
            //	$SEPA->export()->view();

            //Sepa Export Save
            //	$SEPA->export()->save();

            //Sepa Export Save and View
            //	$SEPA->export()->save()->view();

            //Seepa Export View and Save
            	$SEPA->export()->view()->save();

            // SEPA Xml export validation with ISO20022
            //$SEPAXml->export()->validation();

            //SEPA xml export convert to array
            //$SEPAXml->export()->convertToArray();
```
XML File Result
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

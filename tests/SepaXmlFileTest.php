<?php
/**
 * Created by Dumitru Russu.
 * Date: 27.01.2014
 * Time: 22:00
 * ${NAMESPACE}\${NAME}
 */

class SepaXmlFileTest extends \PHPUnit\Framework\TestCase
{
    public function testSaveFile()
    {
        $SEPAXml = new \SEPA\SEPAXmlFile();

        $SEPAXml::$_XML_FILES_REPOSITORY = realpath(__DIR__) . '/xml_files/';
        $SEPAXml::$_FILE_NAME = 'sepa_test.xml';

        //Simple Example of Sepa Xml File Messages
        $SEPAXml::$_MESSAGES = array(
            array(
                'message_id' => 1,
                'group_header' => array(
                    'company_name' => 'Amazing SRL ȘȚțș ыаывпавпва ',
                    'organisation_id' => 'ZZ00001X11111112', //is an optional field
                    'private_id' => 'ZZ00001X11111112' //is an optional field
                ),
                'payment_info' => array(
                    'FRST' => array(
                        'id' => 6222,
                        'creditor_iban' => 'MD24 AG00 0225 1000 1310 4168',
                        'creditor_bic' => 'AABAFI42',
                        'creditor_name' => 'Amazing SRL',
                        'scheme_identifier' => 'FR07ZZZ519993',
                        'requested_collection_date' => '2013-08-06',
//				'proprietary_name' => 'SEPA', //default value is = 'SEPA', You can SET only proprietary_name OR schema_name
//				'schema_name' => 'CORE', // default value is = 'CORE', You can SET only proprietary_name OR schema_name
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
                    )
                )
            )
        );

        $this->assertTrue($SEPAXml->export()->save()->validation('pain.008.001.02'));
    }
}

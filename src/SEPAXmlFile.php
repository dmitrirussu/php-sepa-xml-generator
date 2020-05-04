<?php

namespace SEPA;

use DOMDocument;
use Exception;
use \SEPA\Factory\XMLGeneratorFactory AS SEPAXmlGeneratorFactory;

/**
 * Created By Dumitru Russu, e-mail: dmitri.russu@gmail.com
 *
 * Class SEPAXmlFile
 *
 * @package SEPA
 */
class SEPAXmlFile
{
    /**
     * ISO PATH RULES
     *
     * @var string
     */
    private static $_ISO_PATH_RULES = '/ISO20022_RULES/';
    /**
     * XML Files Repository
     *
     * @var string
     */
    public static $_XML_FILES_REPOSITORY = '/xml_files/';
    /**
     * File Name
     *
     * @var string
     */
    public static $_FILE_NAME = 'sepa_test.xml';
    /**
     * Xml Generator Object
     *
     * @var \SEPA\XMLGenerator
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
     *
     * @var array
     */
    public static $_MESSAGES = array(
        array(
            'message_id' => 123,
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
                    'requested_collection_date' => '2013-08-06',
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
        array(
            'message_id' => 124,
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

    public function __construct()
    {
        $this->xmlGeneratorObject = SEPAXmlGeneratorFactory::createXmlGeneratorObject();
    }

    /**
     * Export Xml File
     *
     * @return $this
     */
    public function export()
    {
        $this->generatedMessages();

        return $this;
    }

    /**
     * Generate Messages
     */
    public function generatedMessages()
    {
        foreach (self::$_MESSAGES as $message) {
            try {
                /** @var $message \SEPA\Message */
                if (is_object($message) && $message instanceof \SEPA\Message) {
                    $this->messageObject = SEPAXmlGeneratorFactory::createXMLMessage(
                        $message->getMessageGroupHeader()
                    );
                } else {
                    $message = $this->objectToArray($message);

                    /** @var $groupHeader \SEPA\GroupHeader */
                    $groupHeader = SEPAXmlGeneratorFactory::createXMLGroupHeader()
                        ->setMessageIdentification($message['message_id'])
                        ->setInitiatingPartyName($message['group_header']['company_name']);

                    //is an optional organisation id
                    if (isset($message['group_header']['organisation_id'])) {
                        $groupHeader->setOrganisationIdentification($message['group_header']['organisation_id']);
                    }
                    //is an option private id
                    if (isset($message['group_header']['private_id'])) {
                        $groupHeader->setPrivateIdentification($message['group_header']['private_id']);
                    }

                    $this->messageObject = SEPAXmlGeneratorFactory::createXMLMessage($groupHeader);
                }

                if (is_object($message) && isset($message->payment_info)) {
                    $paymentInfo = $message->payment_info;
                } elseif (is_object($message) && $message instanceof \SEPA\PaymentInfo) {
                    $paymentInfo = $message->getPaymentInfoObjects();
                } else {
                    $paymentInfo = $message['payment_info'];
                }

                $this->generatePaymentInfo($paymentInfo);

                //add Message To Xml File
                $this->xmlGeneratorObject->addXmlMessage($this->messageObject);
            } catch (Exception $e) {
                //Your Logs here
//				$e->getMessage();

            }
        }
    }

    /**
     * Generate Payment Info
     *
     * @param $paymentInfo
     */
    public function generatePaymentInfo($paymentsInfo)
    {
        //set Message Payment Info
        foreach ($paymentsInfo as $SequenceType => $paymentInfo) {
            try {
                /** @var $paymentInfo \SEPA\PaymentInfo */
                if (is_object($paymentInfo) && $paymentInfo instanceof \SEPA\PaymentInfo) {
                    $this->paymentInfoObject = $paymentInfo;
                } else {
                    $paymentInfo = $this->objectToArray($paymentInfo);
                    //set payment info
                    $this->paymentInfoObject = SEPAXmlGeneratorFactory::createXMLPaymentInfo()
                        ->setPaymentInformationIdentification($paymentInfo['id'])
                        ->setSequenceType($SequenceType)
                        ->setCreditorAccountIBAN($paymentInfo['creditor_iban'])
                        ->setCreditorAccountBIC($paymentInfo['creditor_bic'])
                        ->setCreditorName($paymentInfo['creditor_name'])
                        ->setCreditorSchemeIdentification($paymentInfo['scheme_identifier'])
                        ->setRequestedCollectionDate($paymentInfo['requested_collection_date']);

                    if (isset($paymentInfo['proprietary_name'])) {
                        $this->paymentInfoObject->setUseProprietaryName($paymentInfo['proprietary_name']);
                    } elseif (isset($paymentInfo['schema_name'])) {
                        $this->paymentInfoObject->setUseSchemaNameCore($paymentInfo['schema_name']);
                    }
                }

                if (is_object($paymentInfo) && isset($paymentInfo->transactions)) {
                    $transactions = $paymentInfo->transactions;
                } elseif (is_object($paymentInfo) && $paymentInfo instanceof \SEPA\PaymentInfo) {
                    $transactions = $paymentInfo->getDirectDebitTransactionObjects();
                } else {
                    $transactions = $paymentInfo['transactions'];
                }

                //generate Direct Debit Transactions
                $this->generateDirectDebitTransactions($transactions);

                //add Message Payment Info
                $this->messageObject->addMessagePaymentInfo($this->paymentInfoObject);
            } catch (Exception $e) {
                //Your log here
//				$e->getMessage();

            }
        }
    }

    /**
     * Generate Direct Debit Transactions
     *
     * @param $transactions
     */
    public function generateDirectDebitTransactions($transactions)
    {
        foreach ($transactions as $transaction) {
            try {
                //add Payment Info transactions
                if ($transaction instanceof \SEPA\DirectDebitTransaction) {
                    $this->paymentInfoObject->addDirectDebitTransaction($transaction);
                } else {
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
            } catch (Exception $e) {
                //Your logs here
                //$e->getMessage();
            }
        }
    }

    /**
     * Do Object To array
     *
     * @param $object
     * @return array
     */
    private function objectToArray($object)
    {
        if (is_object($object)) {
            $resultArray = array();
            foreach ($object as $key => $value) {
                $result[$key] = $value;
            }
            return $resultArray;
        }
        return $object;
    }

    /**
     * Save Xml file
     *
     * @param null $filePath
     * @return $this
     */
    public function save($filePath = null)
    {
        $fileName = realpath(__DIR__) . static::$_XML_FILES_REPOSITORY . static::$_FILE_NAME;

        if ($filePath && is_dir(dirname($filePath))) {
            $fileName = $filePath;
        } elseif (is_dir(static::$_XML_FILES_REPOSITORY)) {
            $fileName = static::$_XML_FILES_REPOSITORY . static::$_FILE_NAME;
        }

        $this->xmlGeneratorObject->save($fileName);

        return $this;
    }

    /**
     * View Xml File
     *
     * @return $this
     */
    public function view($withOutOfHeader = false)
    {
        if (!$withOutOfHeader) {
            header("Content-Type:text/xml");
        }

        echo $this->xmlGeneratorObject->getGeneratedXml();
        return $this;
    }

    /**
     * SEPA XML File Validation
     *
     * @param string $messageIdSXMLSchema
     * @param bool   $xmlFile
     * @return bool
     * @throws Exception
     */
    public function validation($messageIdSXMLSchema = 'pain.008.001.02', $xmlFile = false)
    {
        $dom = new DOMDocument();

        if ($xmlFile) {
            $xmlFile = (file_exists(self::$_XML_FILES_REPOSITORY . self::$_FILE_NAME)
                ? self::$_XML_FILES_REPOSITORY . self::$_FILE_NAME
                : realpath(__DIR__) . self::$_XML_FILES_REPOSITORY . self::$_FILE_NAME);
        }

        $xsdFile = realpath(__DIR__) . self::$_ISO_PATH_RULES . $messageIdSXMLSchema . '.xsd';

        if (file_exists($xmlFile)) {
            $dom->load($xmlFile, LIBXML_NOBLANKS);
        } else {
            $dom->loadXML($this->xmlGeneratorObject->getGeneratedXml(), LIBXML_NOBLANKS);
        }

        if (!file_exists($xsdFile)) {
            throw new Exception('XSD File not found!');
        }

        return $dom->schemaValidate($xsdFile);
    }

    /**
     * Convert TO Array
     *
     * @return array
     */
    public function convertToArray()
    {
        $xmlFile = realpath(__DIR__) . self::$_XML_FILES_REPOSITORY . self::$_FILE_NAME;

        if (file_exists($xmlFile)) {
            $xml = simplexml_load_file($xmlFile);
        } else {
            $xml = $this->xmlGeneratorObject->getGeneratedXml();
        }

        return json_decode(json_encode($xml), true);
    }
}

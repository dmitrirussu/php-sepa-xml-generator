<?php

namespace SEPA\Factory;

/**
 * Created by Dumitru Russu. e-mail: dmitri.russu@gmail.com
 * Date: 06.10.2013
 * Time: 15:32
 * Xml Generator Factory
 */
class XMLGeneratorFactory
{
    private function __construct()
    {
    }

    private function __clone()
    {
    }

    /**
     * @return \SEPA\XMLGenerator
     */
    public static function createXmlGeneratorObject($documentPainMode = \SEPA\XMLGenerator::PAIN_008_001_02)
    {
        return new \SEPA\XMLGenerator($documentPainMode);
    }

    /**
     * Create Xml Message
     *
     * @param \SEPA\GroupHeader $setGroupHeader
     * @return \SEPA\Message
     */
    public static function createXMLMessage(?\SEPA\GroupHeader $setGroupHeader = null)
    {
        $message = new \SEPA\Message();

        //set to Message Group Header
        if (!empty($setGroupHeader)) {
            $message->setMessageGroupHeader($setGroupHeader);
        }

        return $message;
    }

    /**
     * @return \SEPA\GroupHeader
     */
    public static function createXMLGroupHeader()
    {
        return new \SEPA\GroupHeader();
    }

    /**
     * @return \SEPA\PaymentInfo
     */
    public static function createXMLPaymentInfo()
    {
        return new \SEPA\PaymentInfo();
    }

    /**
     * @return \SEPA\DirectDebitTransaction
     */
    public static function createXMLDirectDebitTransaction()
    {
        return new \SEPA\DirectDebitTransaction();
    }

    /**
     * @return \SEPA\CreditTransferTransaction
     */
    public static function createXMLCreditTransferTransaction()
    {
        return new \SEPA\CreditTransferTransaction();
    }
}

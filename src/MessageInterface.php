<?php

namespace SEPA;

/**
 * Message Interface
 * Class MessageInterface
 *
 * @package SEPA
 */
interface MessageInterface
{
    public function setMessageGroupHeader(GroupHeader $groupHeaderObject);

    public function getMessageGroupHeader();

    public function addMessagePaymentInfo(PaymentInfo $paymentInfoObject);

    public function getSimpleXMLElementMessage();
}

<?php

namespace SEPA;

/**
 * Payment Info Interface
 * Class PaymentInfoInterface
 *
 * @package SEPA
 */
interface PaymentInfoInterface
{
    public function addDirectDebitTransaction(DirectDebitTransaction $directDebitTransactionObject);

    public function checkIsValidPaymentInfo();

    public function getErrorTransactionsIds();

    public function getSimpleXMLElementPaymentInfo();
}

<?php

namespace SEPA;

/**
 * Created by Dumitru Russu. Email: <dmitri.russu@gmail.com>
 * Date: 04.12.2014
 * Time: 15:47
 * SEPA${NAME}
 */
interface TransactionInterface
{
    public function checkIsValidTransaction();

    public function getSimpleXMLElementTransaction();

    public function getInstructedAmount();

    public function getInstructionIdentification();
}

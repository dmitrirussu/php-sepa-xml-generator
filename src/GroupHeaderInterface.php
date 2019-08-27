<?php

namespace SEPA;

/**
 * Group Header Interface
 * Class GroupHeaderInterface
 * @package SEPA
 */
interface GroupHeaderInterface
{
    public function setNumberOfTransactions($nbTransactions);

    public function getNumberOfTransactions();

    public function getSimpleXmlGroupHeader();
}

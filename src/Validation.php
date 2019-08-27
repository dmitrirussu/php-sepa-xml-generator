<?php

namespace SEPA;

interface Validation
{
    public function unicodeDecode($string);

    public function removeSpaces($string);

    public function checkIBAN($value);

    public function checkBIC($value);

    public function checkStringLength($value);

    public function boolToString($value);

    public function amountToString($value);

    public function sumOfTwoOperands($amountOne, $amountTwo);
}

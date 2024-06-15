<?php

/**
 * Created by Dumitru Russu. e-mail: dmitri.russu@gmail.com
 * Date: 7/8/13
 * Time: 8:50 PM
 * Sepa Validation Rules
 */

namespace SEPA;

use DOMDocument;
use SEPA\Unicode\Unidecode;
use CMPayments\IBAN;

class ValidationRules implements Validation
{
    /**
     * Amount scale
     *
     * @var int
     */
    private static $ROUND_SCALE = 2;

    /**
     * @param $string
     * @return mixed
     */
    public function unicodeDecode($string)
    {
        return Unidecode::decode($string);
    }

    /**
     * @param $string
     * @return mixed
     */
    public function removeSpaces($string)
    {
        return str_replace(' ', '', $string);
    }

    /**
     * @param $value
     * @return bool
     */
    public function checkIBAN($value)
    {
        $iban = new IBAN($value);
        return $iban->validate($value);
    }

    /**
     * Sepa check BIC
     *
     * @param $bic
     * @return bool
     */
    public function checkBIC($bic)
    {
        $bic = str_replace(' ', '', trim($bic));

        if (preg_match('/^[0-9a-z]{4}[a-z]{2}[0-9a-z]{2}([0-9a-z]{3})?\z/i', $bic)) {
            return true;
        }

        return false;
    }

    /**
     * Format an integer as a monetary value.
     *
     * @param $amount
     * @return string
     */
    public function amountToString($amount)
    {
        return number_format($amount, self::$ROUND_SCALE, '.', '');
    }

    /**
     * Do Sum of two Operands
     *
     * @param $amountOne
     * @param $amountTwo
     * @return string
     */
    public function sumOfTwoOperands($amountOne, $amountTwo)
    {
        return bcadd($amountOne, $amountTwo, self::$ROUND_SCALE);
    }

    /**
     * This method convert the boolean value to String
     *
     * @param $value
     * @return string
     */
    public function boolToString($value)
    {
        return ($value === true || $value == 'true' ? 'true' : 'false');
    }

    /**
     * Check string length
     *
     * @param string $value
     * @param int    $length
     * @return bool|string
     */
    public function checkStringLength($value, $length = 0)
    {
        $lengthOfValue = strlen($value);

        if (is_int($value) && $value > 0) {
            $lengthOfValue = ($value > 0 && $value <= 10 ? $value : ceil(log10($value)));
        }

        if ($lengthOfValue > 0 && $lengthOfValue <= $length) {
            return true;
        }

        return false;
    }

    /**
     * This method can be used to validate the generated SEPA SDD or SCT xml files
     *
     * @param $xmlSEPAFile -> path to the generated sepa xml file
     * @param $xsdPainRule -> path to the lib/ISO20022_RULES/pain.008.001.02.xsd
     * @return bool
     * @throws \Exception
     */
    public function validation($xmlSEPAFile, $xsdPainRule)
    {
        if (empty($xmlSEPAFile) && !file_exists($xmlSEPAFile)) {
            throw new \InvalidArgumentException('Missing SEPA XML File');
        }

        if (empty($xsdPainRule) || !file_exists($xsdPainRule)) {
            throw new \InvalidArgumentException('Missing XSD Pain Rule');
        }

        $dom = new DOMDocument();
        $dom->load($xmlSEPAFile, LIBXML_NOBLANKS);

        if (!file_exists($xsdPainRule)) {
            throw new \Exception('XSD File not found!');
        }

        return $dom->schemaValidate($xsdPainRule);
    }
}

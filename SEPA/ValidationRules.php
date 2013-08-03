<?php
/**
 * Created by Dumitru Russu.
 * Date: 7/8/13
 * Time: 8:50 PM
 * To change this template use File | Settings | File Templates.
 */
namespace SEPA {

	/**
	 * Class SepaValidation
	 * @package SEPA
	 */
	interface Validation {
		public function unicodeDecode($string);
		public function removeSpaces($string);
		public function checkIBAN($value);
		public function checkBIC($value);
		public function checkStringLength($value);
		public function boolToString($value);
		public function amountToString($value);
	}

	/**
	 * Class SepaValidationRules
	 * @package SEPA
	 */
	class ValidationRules implements Validation {

		/**
		 * @param $string
		 * @return mixed
		 */
		public function unicodeDecode($string) {
			\Unidecode::$containing_dir = '/unicode_decode/';
			return \Unidecode::decode($string);
		}

		/**
		 * @param $string
		 * @return mixed
		 */
		public function removeSpaces($string) {

			return str_replace(' ', '', $string);
		}

		/**
		 * @param $value
		 * @return bool
		 */
		public function checkIBAN($value) {
			return verify_iban($value);
		}

		/**
		 * Sepa check BIC
		 * @param $bic
		 * @return bool
		 */
		public function checkBIC($bic) {

			$bic = str_replace(' ', '', trim($bic));

			if (preg_match('/^[0-9a-z]{4}[a-z]{2}[0-9a-z]{2}([0-9a-z]{3})?\z/i', $bic)) {

				return true;
			}

			return false;
		}

		/**
		 * Format an integer as a monetary value.
		 * @param $amount
		 * @return string
		 */
		public function amountToString($amount) {

			return sprintf("%01.2f", $amount);
		}

		/**
		 * This method convert the boolean value to String
		 * @param $value
		 * @return string
		 */
		public function boolToString($value) {

			return ($value === true || $value == 'true' ? 'true' : 'false');
		}

		/**
		 * Check string length
		 * @param string $value
		 * @param int $length
		 * @return bool|string
		 */
		public function checkStringLength($value, $length = 0) {

			$lengthOfValue = strlen($value);

			if ( is_int($value) &&  $value > 0 ) {

				$lengthOfValue =  ($value > 0 && $value <= 10 ? $value :  ceil(log10($value)));
			}

			if ( $lengthOfValue > 0 && $lengthOfValue <= $length ) {

				return true;
			}

			return false;
		}
	}
}
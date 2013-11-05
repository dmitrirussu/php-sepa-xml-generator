<?php

/**
 * Created by Dumitru Russu.
 * Date: 7/8/13
 * Time: 8:48 PM
 * Sepa Message NameSpace
 */
namespace SEPA;

/**
 * Message Interface
 * Class MessageInterface
 * @package SEPA
 */
interface MessageInterface {
	public function setMessageGroupHeader(GroupHeader $groupHeaderObject);
	public function getMessageGroupHeader();
	public function addMessagePaymentInfo(PaymentInfo $paymentInfoObject);
	public function getSimpleXMLElementMessage();
}
/**
	 * Class SepaMessage
	 * @package SEPA
	 */
	class Message extends XMLGenerator implements MessageInterface {

		/**
		 * @var$groupHeaderObjects GroupHeader
		 */
		private $groupHeaderObjects;

		/**
		 * @var $message \SimpleXMLElement
		 */
		private $message;

		/**
		 * @var $storeXmlPaymentsInfo \SimpleXMLElement
		 */
		private $storeXmlPaymentsInfo;


		/**
		 * @var array
		 */
		private $paymentInfoObjects = array();

		public function __construct() {
			$this->message = new \SimpleXMLElement('<CstmrDrctDbtInitn></CstmrDrctDbtInitn>');
			$this->storeXmlPaymentsInfo = new \SimpleXMLElement('<payments></payments>');
		}

		/**
		 * Add Group Header
		 * @param GroupHeader $groupHeaderObject
		 */
		public function setMessageGroupHeader(GroupHeader $groupHeaderObject) {
			try {

				if ( is_null($this->groupHeaderObjects) ) {

					$this->groupHeaderObjects = $groupHeaderObject;
				}

			} catch(\Exception $e) {

				$this->writeLog($e->getMessage());
			}

			return $this;
		}

		/**
		 * @return GroupHeader
		 */
		public function getMessageGroupHeader() {

			return $this->groupHeaderObjects;
		}

		/**
		 * Add Message Payment Info
		 * @param PaymentInfo $paymentInfoObject
		 * @throws \Exception
		 */
		public function addMessagePaymentInfo(PaymentInfo $paymentInfoObject) {

			try {

				if ( !($paymentInfoObject instanceof PaymentInfo) ) {

					throw new \Exception('Was not PaymentInfo Object in addMessagePaymentInfo');
				}

			} catch(\Exception $e) {

				$this->writeLog($e->getMessage());
			}

			$paymentInfoObject->resetNumberOfTransactions();
			$paymentInfoObject->resetControlSum();
			$this->paymentInfoObjects[$paymentInfoObject->getSequenceType()] = $paymentInfoObject;
			return $this;
		}

		/**
		 * Get Payment Info Objects
		 * @return array
		 */
		public function getPaymentInfoObjects() {

			return $this->paymentInfoObjects;
		}

		/**
		 * Get Simple Xml Element Message
		 * @return \SimpleXMLElement
		 */
		public function getSimpleXMLElementMessage() {

			/**
			 * @var $paymentInfo PaymentInfo
			 */
			foreach ($this->paymentInfoObjects as $paymentInfo) {
				try {
					if ( !$paymentInfo->checkIsValidPaymentInfo() ) {

						throw new \Exception(ERROR_MSG_INVALID_PAYMENT_INFO . $paymentInfo->getPaymentInformationIdentification());

					}
					$paymentInfo->resetControlSum();
					$paymentInfo->resetNumberOfTransactions();

					$this->simpleXmlAppend($this->storeXmlPaymentsInfo, $paymentInfo->getSimpleXMLElementPaymentInfo());

					$this->getMessageGroupHeader()->setNumberOfTransactions($paymentInfo->getNumberOfTransactions());
					$this->getMessageGroupHeader()->setControlSum($paymentInfo->getControlSum());

				} catch(\Exception $e) {

					$this->writeLog($e->getMessage());

				}

			}

			$this->simpleXmlAppend($this->message, $this->getMessageGroupHeader()->getSimpleXmlGroupHeader());

			foreach (dom_import_simplexml($this->storeXmlPaymentsInfo)->childNodes as $element) {

				$this->simpleXmlAppend($this->message, $element);
			}

			return $this->message;
		}
	}

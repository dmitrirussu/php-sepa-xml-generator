<?php

/**
 * Created by Dumitru Russu. e-mail: dmitri.russu@gmail.com
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
		$this->createMessage();
		$this->storeXmlPaymentsInfo = new \SimpleXMLElement('<payments></payments>');
	}

	private function createMessage() {
		switch($this->getDocumentPainMode()) {
			case self::PAIN_001_001_03: {
				$documentMessage = "<CstmrCdtTrfInitn></CstmrCdtTrfInitn>";
				break;
			}
			case self::PAIN_001_001_02: {
				$documentMessage = "<pain.001.001.002></pain.001.001.002>";
				break;

			}
			default: {
				$documentMessage = "<CstmrDrctDbtInitn></CstmrDrctDbtInitn>";
				break;
			}
		}

		$this->message = new \SimpleXMLElement($documentMessage);
	}

	/**
	 * Add Group Header
	 * @param GroupHeader $groupHeaderObject
	 * @return $this
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
	 * @return $this
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
	 * @throws \Exception
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

		foreach ($this->storeXmlPaymentsInfo->children() as $element) {

			$this->simpleXmlAppend($this->message, $element);
		}

		return $this->message;
	}
}

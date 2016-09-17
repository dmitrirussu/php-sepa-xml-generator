<?php

/**
 * Created by Dumitru Russu. e-mail: dmitri.russu@gmail.com
 * Date: 7/8/13
 * Time: 8:47 PM
 * Sepa Payment Info
 */
namespace SEPA;

/**
 * Payment Info Interface
 * Class PaymentInfoInterface
 * @package SEPA
 */
interface PaymentInfoInterface {
	public function addDirectDebitTransaction(DirectDebitTransaction $directDebitTransactionObject);
	public function checkIsValidPaymentInfo();
	public function getErrorTransactionsIds();
	public function getSimpleXMLElementPaymentInfo();
}
	/**
	 * Class SepaPaymentInfo
	 * @package SEPA
	 */
	class PaymentInfo extends Message implements PaymentInfoInterface {


		/**
		 * Payment Methods
		 */
		const PAYMENT_METHOD_DIRECT_DEBIT = "DD";
		const PAYMENT_METHOD_CREDIT_TRANSFERT = "TRF";

		/**
		 * Specifies a pre-agreed service or level of service between the parties, as published in an external service
		 * level code list.
		 * @var string
		 */
		const SERVICE_LEVEL_CODE = 'SEPA';

		/**
		 * Specifies the local instrument published in an external local instrument code list.
		 * @var string
		 */
		const LOCAL_INSTRUMENT_CODE = 'CORE';

		/**
		 * Name of the identification scheme, in a free text form.
		 * @var string
		 */
		const PROPRIETARY_NAME = 'SEPA';

		/**
		 * Specifies which party/parties will bear the charges associated with the processing of the payment transaction.
		 */
		const CHARGE_BEARER = 'SLEV';

		/**
		 * Unique identification, as assigned by a sending party, to unambiguously identify the payment information group
		 * within the message.
		 * max length 35
		 * @var string
		 */
		private $paymentInformationIdentification = '';

		/**
		 * @var
		 */
		private $numberOfTransactions = 0;

		/**
		 * Payment Info total Amount
		 * @var float
		 */
		private $ctrlSum = 0.00;

		/**
		 * Name by which a party is known and which is usually used to identify that party.
		 * @var string
		 */
		private $creditorName = '';

		/**
		 * Name by which a party is known and which is usually used to identify that party.
		 * @var string
		 */
		private $debitorName = '';

		/**
		 * International Bank Account Number (IBAN) - identifier used internationally by financial institutions
		 * to uniquely identify the account of a customer.
		 * @var string
		 */
		private $creditorAccountIBAN = '';

		/**
		 * International Bank Account Number (IBAN) - identifier used internationally by financial institutions
		 * to uniquely identify the account of a customer.
		 * @var string
		 */
		private $debitorAccountIBAN = '';

		/**
		 * Unique and unambiguous identifier of a financial institution, as assigned under an internationally
		 * recognised or proprietary identification scheme.
		 * @var string
		 */
		private $creditorBIC = '';


		/**
		 * Unique and unambiguous identifier of a financial institution, as assigned under an internationally
		 * recognised or proprietary identification scheme.
		 * @var string
		 */
		private $debitorBIC = '';

		/**
		 * Identifies the direct debit sequence, such as first, recurrent, final or one-off.
		 * @var string
		 */
		private $sequenceType = '';

		/**
		 * Date and time at which the creditor requests that the amount of money is to be collected from the debtor.
		 * @var string
		 */
		private $requestedCollectionDate = '';


		/**
		 * Date and time at which the debitor requests that the amount of money is to be transfered to the creditor.
		 * @var string
		 */
		private $requestedExecutionDate = '';

		/**
		 * Creditor Schema Id
		 * @var string
		 */
		private $creditorSchemeIdentification = '';

		/**
		 * Identifies whether a single entry per individual transaction or a batch entry for the sum of the amounts of
		 * alltransactions within the group of a message is requested.
		 * @var string
		 */
		private $batchBooking = false;
		/**
		 * Specifies the high level purpose of the instruction based on a set of pre-defined categories.
		 * it property is optional
		 * @var string
		 */
		private $categoryPurpose = '';

		/**
		 * This property is optional
		 * @var string
		 */
		private $ultimateCreditor = '';

		/**
		 * This property is optional
		 * @var string
		 */
		private $ultimateCreditorSIRET = '';

		/**
		 * @var bool
		 */
		private $useProprietary = true;

		/**
		 * @var bool
		 */
		private $useSchemaName;

		/**
		 * Direct Debit Transaction objects is a storage of Payment Info transactions for Direct Debit
		 * @var array
		 */
		private $directDebitTransactionObjects = array();


		/**
		 * Credit Transfert Transaction objects is a storage of Payment Info transactions for Credit Transfert
		 * @var array
		 */
		private $creditTransferTransactionObjects = array();

		/**
		 * @var array
		 */
		private $errorTransactionsIds = array();


		/**
		 * This field offer possibility to aggregate many transaction by unique MANDATE-ID,
		 * also can be disabled by ->setAggregation(false).
		 * You have to check with your Bank if Aggregation is required or not.
		 * Option By default = true
		 * @var bool
		 */
		private $aggregatePerMandate = true;


		/**
		 * Specifies the means of payment that will be used to move the amount of money.
		 * Max 35 length
		 * @var string
		 */
		private $paymentMethod = self::PAYMENT_METHOD_DIRECT_DEBIT;

		/**
		 * Specifies the local instrument code.
		 * @var string
		 */
		private $localInstrumentCode = self::LOCAL_INSTRUMENT_CODE;


		public function __construct() {}


		/**
		 * @return boolean
		 */
		public function getAggregatePerMandate() {
			return $this->aggregatePerMandate;
		}

		/**
		 * Set Transactions Aggregation by Unique Mandate ID
		 * @param boolean $aggregatePerMandate
		 * @return $this
		 */
		public function setAggregatePerMandate($aggregatePerMandate)
		{
			$this->aggregatePerMandate = $aggregatePerMandate;
			return $this;
		}



		/**
		 * @return array
		 */
		public function getErrorTransactionsIds() {
			return $this->errorTransactionsIds;
		}

		/**
		 * @return string
		 */
		public function getPaymentInformationIdentification() {

			return $this->paymentInformationIdentification;
		}

		public function getCreditorName() {

			return $this->creditorName;
		}

		public function getDebitorName() {
			return $this->debitorName;
		}

		/**
		 * Unique identification, as assigned by a sending party, to unambiguously identify
		 * the payment information group within the message.
		 * Max length 35
		 * @param $paymentInformationId
		 * @return $this
		 * @throws \Exception
		 */
		public function setPaymentInformationIdentification($paymentInformationId) {

			if ( !$this->checkStringLength($paymentInformationId, 35) ) {

				throw new \Exception(ERROR_MSG_PM_INFO_IDENTIFIER, ERROR_MSG_PM_INFO_IDENTIFIER_CODE);
			}

			$this->paymentInformationIdentification = $paymentInformationId;
			return $this;
		}

		/**
		 * Identifies the direct debit sequence, such as first, recurrent, final or one-off.
		 * @param $SeqTp
		 * @return $this
		 * @throws \Exception
		 */
		public function setSequenceType($SeqTp) {

			if ( empty($SeqTp) || is_null($SeqTp) ) {
				throw new \Exception(ERROR_MSG_PM_SEQUENCE_TYPE);
			}
			$this->sequenceType = $SeqTp;
			return $this;
		}

		public function getSequenceType() {

			return $this->sequenceType;
		}


		/**
		 * @param $ReqdColltnDt
		 * @return $this
		 */
		public function setRequestedCollectionDate($ReqdColltnDt) {
			$this->requestedCollectionDate = $ReqdColltnDt;
			return $this;
		}

		/**
		 * @param $requestedExecutionDate
		 * @return $this
		 */
		public function setRequestedExecutionDate($requestedExecutionDate) {
			$this->requestedExecutionDate = $requestedExecutionDate;
			return $this;
		}



		/**
		 * @param bool $default
		 */
		public function setUseProprietaryName($default = true) {
			$this->useProprietary = $default;
		}

		/**
		 * @return bool
		 */
		public function getUseProprietaryName() {
			return $this->useProprietary;
		}

		/**
		 * @param bool $default
		 */
		public function setUseSchemaNameCore($default) {
			$this->useSchemaName = $default;
		}

		/**
		 * @return bool
		 */
		public function getUseSchemaNameCore() {
			return $this->useSchemaName;
		}

		/**
		 * Date and time at which the creditor requests that the amount of money is to be collected from the debtor.
		 */
		public function getRequestedCollectionDate() {

			if ( empty($this->requestedCollectionDate) ) {
				$dateTime = new \DateTime();
				$this->requestedCollectionDate = $dateTime->format('Y-m-d');
			}

			return $this->requestedCollectionDate;
		}

		/**
		 * Date and time at which the debitor requests that the amount of money is to be transfered to the creditor.
		 */
		public function getRequestedExecutionDate() {

			if ( empty($this->requestedExecutionDate) ) {
				$dateTime = new \DateTime();
				$this->requestedExecutionDate = $dateTime->format('Y-m-d');
			}

			return $this->requestedExecutionDate;
		}

		/**
		 * For example PopFax
		 * @param $creditorName
		 * @return $this
		 * @throws \Exception
		 */
		public function setCreditorName($creditorName) {
			$creditorName = $this->unicodeDecode($creditorName);

			if ( !$this->checkStringLength($creditorName, 140) ) {

				throw new \Exception(ERROR_MSG_CREDITOR_NAME, ERROR_MSG_CREDITOR_NAME_CODE);
			}

			$this->creditorName = $creditorName;
			return $this;
		}

		public function setDebitorName($debitorName) {
			$debitorName = $this->unicodeDecode($debitorName);

			if ( !$this->checkStringLength($debitorName, 70) ) {
				throw new \Exception(ERROR_MSG_DEBITOR_NAME, ERROR_MSG_DEBITOR_NAME_CODE);
			}

			$this->debitorName = $debitorName;
			return $this;
		}

		/**
		 * International Bank Account Number (IBAN) - identifier used internationally by financial institutions to
		 * uniquely identify the account of a customer.
		 * max 34 length
		 * @param $creditorAccountIBAN
		 * @return $this
		 * @throws \Exception
		 */
		public function setCreditorAccountIBAN($creditorAccountIBAN) {

			$creditorAccountIBAN = $this->removeSpaces($creditorAccountIBAN);

			if ( !$this->checkIBAN($creditorAccountIBAN) ) {

				throw new \Exception(ERROR_MSG_CREDITOR_IBAN, ERROR_MSG_CREDITOR_IBAN_CODE);
			}

			$this->creditorAccountIBAN = $creditorAccountIBAN;
			return $this;
		}

		/**
		 * @return string
		 */
		public function getCreditorAccountIBAN() {

			return $this->creditorAccountIBAN;
		}


		/**
		 * International Bank Account Number (IBAN) - identifier used internationally by financial institutions to
		 * uniquely identify the account of a customer.
		 * max 34 length
		 * @param $debitorAccountIBAN
		 * @return $this
		 * @throws \Exception
		 */
		public function setDebitorAccountIBAN($debitorAccountIBAN) {
			$debitorAccountIBAN = $this->removeSpaces($debitorAccountIBAN);
			if ( !$this->checkIBAN($debitorAccountIBAN) ) {

				throw new \Exception(ERROR_MSG_DEBITOR_IBAN, ERROR_MSG_DEBITOR_IBAN_CODE);
			}

			$this->debitorAccountIBAN = $debitorAccountIBAN;
			return $this;
		}

		/**
		 * @return string
		 */
		public function getDebitorAccountIBAN() {

			return $this->debitorAccountIBAN;
		}

		/**
		 * Bank Identifier Code.
		 * @param $creditorBIC
		 * @return $this
		 * @throws \Exception
		 */
		public function setCreditorAccountBIC($creditorBIC) {
			if ( !$this->checkBIC($creditorBIC) ) {

				throw new \Exception(ERROR_MSG_CREDITOR_BIC, ERROR_MSG_CREDITOR_BIC_CODE);
			}

			$this->creditorBIC = $creditorBIC;
			return $this;
		}

		/**
		 * @return string
		 */
		public function getCreditorAccountBIC() {

			return $this->creditorBIC;
		}


		/**
		 * Bank Identifier Code.
		 * @param $creditorBIC
		 * @return $this
		 * @throws \Exception
		 */
		public function setDebitorAccountBIC($debitorBIC) {
			if ( !$this->checkBIC($debitorBIC) ) {

				throw new \Exception(ERROR_MSG_DEBITOR_BIC, ERROR_MSG_DEBITOR_BIC_CODE);
			}

			$this->debitorBIC = $debitorBIC;
			return $this;
		}

		/**
		 * @return string
		 */
		public function getDebitorAccountBIC() {

			return $this->debitorBIC;
		}

		/**
		 * Creditor Schema Id
		 * @param $creditorSchemaId
		 * @return $this
		 * @throws \Exception
		 */
		public function setCreditorSchemeIdentification($creditorSchemaId) {
			if ( empty($creditorSchemaId) || is_null($creditorSchemaId) ) {

				throw new \Exception(ERROR_MSG_PM_CREDITOR_SCHEME_IDENTIFICATION, ERROR_MSG_PM_CREDITOR_SCHEME_IDENTIFICATION_CODE);
			}
			$this->CreditorSchemeIdentification = $creditorSchemaId;
			return $this;
		}

		/**
		 * @return string
		 */
		public function getCreditorSchemeIdentification() {

			return $this->creditorSchemeIdentification;
		}

		/**
		 * This property is optional
		 * @param $UltimateCreditor
		 * @return $this
		 */
		public function setUltimateCreditor($UltimateCreditor) {

			$this->ultimateCreditor = $UltimateCreditor;
			return $this;
		}

		/**
		 * This property is optional
		 * @param $UltimateCreditorSIRET
		 * @return $this
		 */
		public function setUltimateCreditorSIRET($UltimateCreditorSIRET) {

			$this->ultimateCreditorSIRET = $UltimateCreditorSIRET;
			return $this;
		}

		/**
		 * @return string
		 */
		public function getUltimateCreditor() {

			return $this->ultimateCreditor;
		}

		/**
		 * @return string
		 */
		public function getUltimateCreditorSIRET() {

			return $this->ultimateCreditorSIRET;
		}

		/**
		 * Identifies whether a single entry per individual transaction or a batch entry for the sum of the amounts of
		 * all transactions within the group of a message is requested.
		 * @param $value
		 * @return $this
		 * @throws \Exception
		 */
		public function setBatchBooking($value) {

			if ( is_null($value) || empty($value)) {

				throw new \Exception(ERROR_MSG_PM_BATCH_BOOKING, ERROR_MSG_PM_BATCH_BOOKING_CODE);
			}

			$this->batchBooking = $value;
			return $this;
		}

		/**
		 * @return bool|string
		 */
		public function getBatchBooking() {

			return $this->batchBooking;
		}

		/**
		 * This property is optional
		 * @param $CategoryPurpose
		 * @return $this
		 */
		public function setCategoryPurpose($CategoryPurpose) {

			$this->categoryPurpose = $CategoryPurpose;
			return $this;
		}

		/**
		 * @return mixed
		 */
		public function getCategoryPurpose() {

			return $this->categoryPurpose;
		}

		/**
		 * Change the default local instrument code to the specified in the argument.
		 * @param $localInstrumentCode The new code for local instrument.
		 * @return $this
		 */
		public function setLocalInstrumentCode($localInstrumentCode) {

			$this->localInstrumentCode = $localInstrumentCode;

			return $this;
		}

		/**
		 * Get the local instrument code which will be used.
		 * @return string
		 */
		public function getLocalInstrumentCode() {

			return $this->localInstrumentCode;
		}

		/**
		 * Payment info Direct Debit Transactions Object
		 * @param $directDebitTransactionObject DirectDebitTransaction
		 * @throws \Exception
		 * @return $this
		 */
		public function addDirectDebitTransaction(DirectDebitTransaction $directDebitTransactionObject) {
			if (! empty($this->creditTransfertTransactionObjects)) {
				throw new \Exception(ERROR_MSG_PM_ONLY_ONE_TYPE, ERROR_MSG_PM_ONLY_ONE_TYPE_CODE);
			}

			try {
				if ($this->aggregatePerMandate) {
					if ( isset($this->directDebitTransactionObjects[$directDebitTransactionObject->getMandateIdentification()]) ) {
						/** @var $existTransaction \SEPA\DirectDebitTransaction */
						$existTransaction = $this->directDebitTransactionObjects[$directDebitTransactionObject->getMandateIdentification()];

						//sum of Instructed Amount
						$existTransaction->setInstructedAmount(
							$this->sumOfTwoOperands($existTransaction->getInstructedAmount(),
								$directDebitTransactionObject->getInstructedAmount())
						);

						$existTransaction->setEndToEndIdentification($directDebitTransactionObject->getEndToEndIdentification());
					}
					else {

						$this->directDebitTransactionObjects[$directDebitTransactionObject->getMandateIdentification()] = $directDebitTransactionObject;
					}
				}
				else {
					$this->directDebitTransactionObjects[] = $directDebitTransactionObject;
				}

			} catch(\Exception $e) {

				$this->writeLog($e->getMessage());
			}

			return $this;
		}

		/**
		 * Get Direct Debit Transactions
		 * @return array
		 */
		public function getDirectDebitTransactionObjects() {

			return $this->directDebitTransactionObjects;
		}

		/**
		 * Get Credit Transfer Transactions
		 * @return array
		 */
		public function getCreditTransferTransactionObjects() {
			return $this->creditTransferTransactionObjects;
		}

		/**
		 * @param CreditTransferTransaction $creditTransferTransactionObject
		 * @return $this
		 * @throws \Exception
		 */
		public function addCreditTransferTransaction(CreditTransferTransaction $creditTransferTransactionObject) {

			if (!empty($this->directDebitTransactionObjects)) {
				throw new \Exception(ERROR_MSG_PM_ONLY_ONE_TYPE, ERROR_MSG_PM_ONLY_ONE_TYPE_CODE);
			}

			if ( $this->getPaymentMethod() !== self::PAYMENT_METHOD_CREDIT_TRANSFERT) {
				$this->setPaymentMethod(self::PAYMENT_METHOD_CREDIT_TRANSFERT);
			}

			$this->creditTransferTransactionObjects[] = $creditTransferTransactionObject;

			return $this;
		}

		/**
		 * @param $value
		 * @return $this
		 */
		public function addToCtrlSum($value) {

			$this->ctrlSum += $value;
			return $this;
		}

		/**
		 * @return string
		 */
		public function getControlSum() {

			return $this->amountToString($this->ctrlSum);
		}

		public function addToNumberOfTransactions($value) {

			$this->numberOfTransactions += $value;
			return $this;
		}

		/**
		 * Get Number of Transactions
		 * @return bool|string
		 */
		public function getNumberOfTransactions() {

			return $this->numberOfTransactions;
		}

		public function resetNumberOfTransactions() {
			$this->numberOfTransactions = 0;
			return $this;
		}

		public function resetControlSum() {
			$this->ctrlSum = 0;
			return $this;
		}

		/**
		 * Specifies the means of payment that will be used to move the amount of money.
		 * @return bool
		 */
		public function checkIsValidPaymentInfo() {

			//For the BIC and IBAN, use their own validation methods
			if ( !$this->getPaymentInformationIdentification()
				|| ( $this->getPaymentMethod() == self::PAYMENT_METHOD_DIRECT_DEBIT
					&& (
						!$this->getCreditorAccountIBAN()
						|| !$this->getCreditorAccountBIC()
					)
				)
				|| ( $this->getPaymentMethod() == self::PAYMENT_METHOD_CREDIT_TRANSFERT
					&& (
						!$this->getDebitorAccountIBAN()
						|| !$this->getDebitorAccountBIC()
					)
				)
			) {
				return false;
			}
			return true;
		}

		/**
		 * Get the means of payment that will be used to move the amount of money.
		 * @return string
		 */
		public function getPaymentMethod() {
			return $this->paymentMethod;
		}

		/**
		 * Set the means of payment that will be used to move the amount of money.
		 * @param $paymentMethod
		 * @return $this
		 */
		public function setPaymentMethod($paymentMethod) {
			$this->paymentMethod = $paymentMethod;
			return $this;
		}


		/**
		 * Get Simple XML Element Payment Info is a method which generate a payment info xml elements
		 * @throws \Exception
		 * @return \SimpleXMLElement
		 */
		public function getSimpleXMLElementPaymentInfo() {
			//Customers Direct Debit Information
			$paymentInfo = new \SimpleXMLElement("<PmtInf></PmtInf>");

			$paymentInfo->addChild('PmtInfId', $this->getPaymentInformationIdentification());
			if (!$this->getPaymentMethod()) {
				throw new \Exception(ERROR_MSG_PM_METHOD_NOT_DEFINED, ERROR_MSG_PM_METHOD_NOT_DEFINED_CODE);
			}
			$paymentInfo->addChild('PmtMtd', $this->getPaymentMethod());

			if ($this->getDocumentPainMode() != self::PAIN_001_001_03) {
				if ( !$this->getCreditTransferTransactionObjects() ) {
					$paymentInfo->addChild('BtchBookg', $this->boolToString($this->getBatchBooking()));
					$paymentInfo->addChild('NbOfTxs', $this->getNumberOfTransactions());
					$paymentInfo->addChild('CtrlSum', $this->getControlSum());
				}

			}



			$this->addPaymentTypeInfoToXml($paymentInfo);


			switch($this->getPaymentMethod()) {
				case self::PAYMENT_METHOD_DIRECT_DEBIT:
					$this->addCreditorFieldsToXml($paymentInfo);
					break;
				case self::PAYMENT_METHOD_CREDIT_TRANSFERT:
					$this->addDebitorFieldsToXml($paymentInfo);
					break;
			}


			$this->resetControlSum();
			$this->resetNumberOfTransactions();

			/**
			 * @var $transaction TransactionInterface
			 */
			foreach ($this->aggregateTransactions() as $transaction) {

				//check if is Valid Transaction
				if ( $transaction->checkIsValidTransaction() ) {
					//get the xml for the transaction object
					$xmlTransaction = $transaction->getSimpleXMLElementTransaction();

					//Add each paymentInfo to the PaymentInfo node
					$this->simpleXmlAppend($paymentInfo, $xmlTransaction);

					$this->addToNumberOfTransactions(1);
					$this->addToCtrlSum($transaction->getInstructedAmount());
				} else {
					$this->writeLog(ERROR_MSG_INVALID_TRANSACTION . $transaction->getInstructionIdentification() );

					//if a transaction is rejected, we need to update the number of valid transactions and the total amount
					$this->errorTransactionsIds[] = $transaction->getInstructionIdentification();
				}
			}


			//Once we have taken care of all the transactions, we can update the total number of transactions and the control sum
			if ($this->getDocumentPainMode() != self::PAIN_001_001_03) {
				$paymentInfo->NbOfTxs = $this->getNumberOfTransactions();
				$paymentInfo->CtrlSum = $this->getControlSum();
			}

			return $paymentInfo;
		}

		protected function aggregateTransactions() {
			return array_merge(
				$this->directDebitTransactionObjects,
				$this->creditTransferTransactionObjects
			);
		}



		protected function addPaymentTypeInfoToXml(\SimpleXMLElement $paymentInfo) {

			$paymentTypeInfo = $paymentInfo->addChild('PmtTpInf');
			$serviceLevel = $paymentTypeInfo->addChild('SvcLvl');
			$serviceLevel->addChild('Cd', self::SERVICE_LEVEL_CODE);

			$localInstrument = $paymentTypeInfo->addChild('LclInstrm');
			$localInstrument->addChild('Cd', $this->getLocalInstrumentCode());

			if ( $this->getSequenceType() && $this->getDocumentPainMode() === self::PAIN_008_001_02 ) {
				$paymentTypeInfo->addChild('SeqTp', $this->getSequenceType());
			}


			//This property is optional
			if ( $this->getCategoryPurpose() ) {

				$paymentTypeInfo->addChild('CtgyPurp', $this->getCategoryPurpose());
			}

			switch($this->getPaymentMethod()) {
				case self::PAYMENT_METHOD_DIRECT_DEBIT:
					$paymentInfo->addChild('ReqdColltnDt', $this->getRequestedCollectionDate());
					break;
				case self::PAYMENT_METHOD_CREDIT_TRANSFERT:
					$paymentInfo->addChild('ReqdExctnDt', $this->getRequestedExecutionDate());
					break;
			}
		}

		/**
		 * Add Creditor Fields Information to XML Document
		 * @param \SimpleXMLElement $paymentInfo
		 */
		protected function addCreditorFieldsToXml(\SimpleXMLElement $paymentInfo){
			$creditor = $paymentInfo->addChild('Cdtr');
			$creditor->addChild('Nm', $this->getCreditorName());

			$creditorAccount = $paymentInfo->addChild('CdtrAcct');
			$creditorAccountID = $creditorAccount->addChild('Id');
			$creditorAccountID->addChild('IBAN', $this->getCreditorAccountIBAN());

			$creditorAgent = $paymentInfo->addChild('CdtrAgt');
			$financialInstitutionIdentification = $creditorAgent->addChild('FinInstnId');
			$financialInstitutionIdentification->addChild('BIC', $this->getCreditorAccountBIC());


			//UltimateCreditor optional
			if ( !empty($this->UltimateCreditor) ) {
				//UltimateCreditorSIRET optional
				if ( !empty($this->UltimateCreditorSIRET) ) {
					$ultimateCreditor = $paymentInfo->addChild('UltmtCdtr');
					$ultimateCreditor->addChild('Nm', $this->getUltimateCreditor());
					$ultimateCreditorOthr = $ultimateCreditor->addChild('Id')->addChild('OrgId')->addChild('Othr');
					$ultimateCreditorOthr->addChild('Id', $this->getUltimateCreditorSIRET());
					$ultimateCreditorOthr->addChild('SchmeNm')->addChild('Prtry', 'SIRET');
				} else {
					$paymentInfo->addChild('UltmtCdtr', $this->getUltimateCreditor());
				}
			}

			$paymentInfo->addChild('ChrgBr', self::CHARGE_BEARER);

			$creditorSchemeIdentification = $paymentInfo->addChild('CdtrSchmeId');
			$creditorSchemeIdentificationID = $creditorSchemeIdentification->addChild('Id');
			$privateIdentification = $creditorSchemeIdentificationID->addChild('PrvtId');
			$othr = $privateIdentification->addChild('Othr');

			if ( !empty($this->CreditorSchemeIdentification) ) {
				$othr->addChild('Id', $this->CreditorSchemeIdentification);
			}

			$schemeName = $othr->addChild('SchmeNm');

			if ( $this->getUseProprietaryName() && !is_string($this->getUseSchemaNameCore()) ) {

				$schemeName->addChild('Prtry', (!is_bool($this->getUseProprietaryName()) ?
					$this->getUseProprietaryName() : self::PROPRIETARY_NAME ));
			}
			elseif ( $this->getUseSchemaNameCore() ) {

				$schemeName->addChild('Cd', (!is_bool($this->getUseSchemaNameCore()) ?
					$this->getUseSchemaNameCore() : self::LOCAL_INSTRUMENT_CODE));
			}
		}

		/**
		 * Add Debitor's Fields information to XML Document
		 * @param \SimpleXMLElement $paymentInfo
		 */
		public function addDebitorFieldsToXml(\SimpleXMLElement $paymentInfo) {
			$debitor = $paymentInfo->addChild('Dbtr');
			$debitor->addChild('Nm', $this->getDebitorName());

			$debitorAccount = $paymentInfo->addChild('DbtrAcct');
			$debitorAccountID = $debitorAccount->addChild('Id');
			$debitorAccountID->addChild('IBAN', $this->getDebitorAccountIBAN());

			$debitorAgent = $paymentInfo->addChild('DbtrAgt');
			$financialInstitutionIdentification = $debitorAgent->addChild('FinInstnId');
			$financialInstitutionIdentification->addChild('BIC', $this->getDebitorAccountBIC());

			$paymentInfo->addChild('ChrgBr', self::CHARGE_BEARER);
		}
	}

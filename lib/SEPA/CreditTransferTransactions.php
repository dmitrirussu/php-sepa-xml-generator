<?php
/**
 * Created by Ruben Podadera. e-mail: ruben.podadera@gmail.com
 * Date: 2/12/14
 * Time: 12:02 PM
 * Credit Transfert Transactions
 */

namespace SEPA;

/**
 * Class SepaDirectDebitTransaction
 * @package SEPA
 */
class CreditTransferTransaction extends PaymentInfo implements TransactionInterface {
	const DEFAULT_CURRENCY = 'EUR';

    /**
     * Unique identification as assigned by an instructing party for an instructed party to unambiguously identify
     * the instruction.
     * @var string
     */
    private $InstructionIdentification = '';

    /**
     *Unique identification assigned by the initiating party to unumbiguously identify the transaction.
     * This identification is passed on, unchanged, throughout the entire end-to-end chain.
     * @var string
     */
    private $EndToEndIdentification = '';


    /**
     * Amount of money to be moved between the debtor and creditor, before deduction of charges, expressed in
     * the currency as ordered by the initiating party.
     * @var float
     */
    private $InstructedAmount = 0.00;

    /**
     * Credit Bank BIC
     * @var string
     */
    private $BIC = '';

    /**
     * Credit IBAN
     * @var string
     */
    private $IBAN = '';


    /**
     * Information supplied to enable the matching/reconciliation of an entry with the items that the payment is
     * intended to settle, such as commercial invoices in an accounts' receivable system, in an unstructured form.
     * max 140 length
     * @var string
     */
    private $creditInvoice = '';


    /**
     * Creditor Name
     * @var string
     */
    private $creditorName = '';


    private $currency = '';

    /**
     * @param $instructionIdentifier
     * @return $this
     */
    public function setInstructionIdentification($instructionIdentifier) {
        $this->InstructionIdentification = $instructionIdentifier;
        return $this;
    }

    /**
     * @return string
     */
    public function getInstructionIdentification() {
        return $this->InstructionIdentification;
    }

    /**
     * @param $instructionIdentifierEndToEnd
     * @return $this
     */
    public function setEndToEndIdentification($instructionIdentifierEndToEnd) {
        $this->EndToEndIdentification = $instructionIdentifierEndToEnd;
        return $this;
    }

    /**
     * @return string
     */
    public function getEndToEndIdentification() {
        return $this->EndToEndIdentification;
    }

    /**
     * Amount of money to be moved between the debtor and creditor, before deduction of charges, expressed in
     * the currency as ordered by the initiating party.
     * @param $amount
     * @return $this
     */
    public function setInstructedAmount($amount) {
        $this->InstructedAmount = $this->amountToString($amount);
        return $this;
    }

    public function getInstructedAmount() {
        return $this->InstructedAmount;
    }

    /**
     * @return string
     */
    public function getBIC() {
        return $this->BIC;
    }

    /**
     * Financial institution servicing an account for the creditor.
     * Bank Identifier Code.
     * max length
     * @param $BIC
     * @return $this
     */
    public function setBIC($BIC) {

        $this->BIC = $this->removeSpaces($BIC);

        return $this;
    }

    /**
     * @return string
     */
    public function getIBAN() {

        return $this->IBAN;
    }

    /**
     * Credit IBAN
     * max  34 length
     * @param $IBAN
     * @throws \Exception
     * @return $this
     */
    public function setIBAN($IBAN) {
        $IBAN = $this->removeSpaces($IBAN);

        if ( !$this->checkIBAN($IBAN) ) {

            throw new \Exception(ERROR_MSG_DD_IBAN . $this->getInstructionIdentification());
        }
        $this->IBAN = $IBAN;
        return $this;
    }


    /**
     * @return string
     */
    public function getCreditInvoice() {
        return $this->creditInvoice;
    }


    /**
     * Credit Invoice
     * @param $invoice
     * @return $this
     * @throws \Exception
     */
    public function setCreditInvoice($invoice) {
        if ( !$this->checkStringLength($invoice, 140) ) {

            throw new \Exception(ERROR_MSG_DD_INVOICE_NUMBER . $this->getInstructionIdentification());
        }
        $this->creditInvoice = $invoice;
        return $this;
    }


    /**
     * @return string
     */
    public function getCreditorName() {
        return $this->creditorName;
    }

    /**
     * Name by which a party is known and which is usually used to identify that party.
     * @param $name
     * @return $this
     * @throws \Exception
     */
    public function setCreditorName($name) {
        if ( !$this->checkStringLength($name, 70) ) {

            throw new \Exception(ERROR_MSG_DD_NAME . $this->getInstructionIdentification());
        }
        $this->creditorName = $name;
        return $this;
    }

    public function setCurrency($currency) {
        $this->currency = strtoupper($currency);
        return $this;
    }

    public function getCurrency() {
        if ( empty($this->currency) || is_null($this->currency) ) {

            $this->currency = self::DEFAULT_CURRENCY;
        }
        return $this->currency;
    }



    public function checkIsValidTransaction()
    {
        if ( !$this->getBIC() ||  !$this->getIBAN() || !$this->getCreditorName()) {
           return false;
        }
        return true;
    }

    public function getSimpleXMLElementTransaction() {
        $creditTransferTransactionInformation = new \SimpleXMLElement('<CdtTrfTxInf></CdtTrfTxInf>');

        $paymentIdentification = $creditTransferTransactionInformation->addChild('PmtId');
        $paymentIdentification->addChild('InstrId', $this->getInstructionIdentification());
        $paymentIdentification->addChild('EndToEndId', $this->getEndToEndIdentification());

        $amount = $creditTransferTransactionInformation->addChild('Amt');
        $amount->addChild('InstdAmt', $this->getInstructedAmount())
            ->addAttribute('Ccy', $this->getCurrency());

        $creditorAgent  = $creditTransferTransactionInformation->addChild('CdtrAgt');
        $financialInstitution = $creditorAgent->addChild('FinInstnId');
        $financialInstitution->addChild('BIC', $this->getBIC());


        $creditor = $creditTransferTransactionInformation->addChild("Cdtr");
        $creditor->addChild("Nm", $this->getCreditorName());

        $creditTransferTransactionInformation->addChild('CdtrAcct')
            ->addChild('Id')
            ->addChild('IBAN', $this->getIBAN());

		if ( $this->getCreditInvoice() ) {
			$creditTransferTransactionInformation->addChild('RmtInf')
				->addChild('Ustrd', $this->getCreditInvoice());
		}

        return $creditTransferTransactionInformation;

    }
}
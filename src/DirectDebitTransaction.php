<?php

/**
 * Created by Dumitru Russu. e-mail: dmitri.russu@gmail.com
 * Date: 7/8/13
 * Time: 8:50 PM
 * Direct Debit Transactions
 */

namespace SEPA;

/**
 * Class SepaDirectDebitTransactions
 *
 * @package SEPA
 */
class DirectDebitTransaction extends PaymentInfo implements TransactionInterface
{
    /**
     * BIC Code not provided
     */
    const BIC_NOTPROVIDED = 'NOTPROVIDED';
    /**
     * Direct Debit Currency
     */
    const CURRENCY = 'EUR';
    /**
     * Unique identification as assigned by an instructing party for an instructed party to unambiguously identify
     * the instruction.
     *
     * @var string
     */
    private $InstructionIdentification = '';
    /**
     *Unique identification assigned by the initiating party to unumbiguously identify the transaction.
     * This identification is passed on, unchanged, throughout the entire end-to-end chain.
     *
     * @var string
     */
    private $EndToEndIdentification = '';
    private $currency = '';
    /**
     * Amount of money to be moved between the debtor and creditor, before deduction of charges, expressed in
     * the currency as ordered by the initiating party.
     *
     * @var float
     */
    private $InstructedAmount = 0.00;
    /**
     * Unique identification, as assigned by the creditor, to unambiguously identify the mandate. SDD
     * max 35 length
     *
     * @var string
     */
    private $MandateIdentification = '';
    /**
     * Direct Debit Transaction DateTime
     *
     * @var string
     */
    private $DateOfSignature = '';
    /**
     * Direct Debit Electronic Signature, max 1025 length
     * @var string
     */
    private $electronicSignature = '';
    /**
     * Debit Bank BIC
     *
     * @var string
     */
    private $BIC = '';
    /**
     * Debit Name
     *
     * @var string
     */
    private $DebtorName = '';
    /**
     * Debitor Country Code, [A-Z]{2,2} ISO 3166
     * @var string
     */
    private $debtorCountry = '';
    /**
     * Debitor Address line 1, max 70 length
     * @var string
     */
    private $debtorAddressLine1 = '';
    /**
     * Debitor Addesss line 2, max 70 length
     * @var string
     */
    private $debtorAddressLine2 = '';
    /**
     * Debitor Organization Identification, max 35 length
     * @var string
     */
    private $debtorOrganizationIdentification = '';
    /**
     * Debitor Private Identification, max 35 length
     * @var string
     */
    private $debtorPrivateIdentification = '';

    /**
     * Direct Debit IBAN
     *
     * @var string
     */
    private $IBAN = '';
    /**
     * Information supplied to enable the matching/reconciliation of an entry with the items that the payment is
     * intended to settle, such as commercial invoices in an accounts' receivable system, in an unstructured form.
     * max 140 length
     *
     * @var string
     */
    private $directDebitInvoice = '';

    /**
     * @return string
     */
    public function getInstructionIdentification()
    {
        return $this->InstructionIdentification;
    }

    /**
     * @return string
     */
    public function getEndToEndIdentification()
    {
        return $this->EndToEndIdentification;
    }

    /**
     * @return string
     */
    public function getMandateIdentification()
    {
        return $this->MandateIdentification;
    }

    /**
     * @return string
     */
    public function getDateOfSignature()
    {
        return $this->DateOfSignature;
    }

    /**
     * @return string
     */
    public function getElectronicSignature()
    {
        return $this->electronicSignature;
    }

    /**
     * @return string
     */
    public function getBIC()
    {
        return $this->BIC;
    }

    /**
     * @return string
     */
    public function getIBAN()
    {
        return $this->IBAN;
    }

    /**
     * @return string
     */
    public function getDebtorName()
    {
        return $this->DebtorName;
    }

    /**
     * @return string
     */
    public function getDebtorCountry()
    {
        return $this->debtorCountry;
    }

    /**
     * @return string
     */
    public function getDebtorAddressLine1()
    {
        return $this->debtorAddressLine1;
    }

    /**
     * @return string
     */
    public function getDebtorAddressLine2()
    {
        return $this->debtorAddressLine2;
    }

    /**
     * @return string
     */
    public function getDebtorOrganizationIdentification()
    {
        return $this->debtorOrganizationIdentification;
    }

    /**
     * @return string
     */
    public function getDebtorPrivateIdentification()
    {
        return $this->debtorPrivateIdentification;
    }

    /**
     * @return string
     */
    public function getDirectDebitInvoice()
    {
        return $this->directDebitInvoice;
    }

    /**
     * @param $instructionIdentifier
     * @return $this
     */
    public function setInstructionIdentification($instructionIdentifier)
    {
        $this->InstructionIdentification = $instructionIdentifier;
        return $this;
    }

    /**
     * @param $instructionIdentifierEndToEnd
     * @return $this
     */
    public function setEndToEndIdentification($instructionIdentifierEndToEnd)
    {
        $this->EndToEndIdentification = $instructionIdentifierEndToEnd;
        return $this;
    }

    /**
     * Amount of money to be moved between the debtor and creditor, before deduction of charges, expressed in
     * the currency as ordered by the initiating party.
     *
     * @param $amount
     * @return $this
     */
    public function setInstructedAmount($amount)
    {
        $this->InstructedAmount = $this->amountToString($amount);
        return $this;
    }

    public function setCurrency($currency)
    {
        $this->currency = strtoupper($currency);
        return $this;
    }

    public function getCurrency()
    {
        if (empty($this->currency) || is_null($this->currency)) {
            $this->currency = self::CURRENCY;
        }
        return $this->currency;
    }

    /**
     * Unique identification, as assigned by the creditor, to unambiguously identify the mandate.
     *
     * @param $directDebitSDD
     * @return $this
     */
    public function setMandateIdentification($directDebitSDD)
    {
        $this->MandateIdentification = $directDebitSDD;
        return $this;
    }

    /**
     * @param $directDebitDateTime
     * @return $this
     */
    public function setDateOfSignature($directDebitDateTime)
    {
        $this->DateOfSignature = $directDebitDateTime;
        return $this;
    }

    /**
     * @param $electronicSignature
     * @return $this
     */
    public function setElectronicSignature($electronicSignature)
    {
        $this->electronicSignature = $electronicSignature;

        return $this;
    }

    /**
     * Financial institution servicing an account for the debtor.
     * Bank Identifier Code.
     * max length
     *
     * @param $BIC
     * @return $this
     */
    public function setDebitBIC($BIC)
    {
        $this->BIC = $this->removeSpaces($BIC);

        return $this;
    }

    /**
     * Name by which a party is known and which is usually used to identify that party.
     *
     * @param $name
     * @return $this
     * @throws \Exception
     */
    public function setDebtorName($name)
    {
        $name = $this->unicodeDecode($name);

        if (!$this->checkStringLength($name, 140)) {
            throw new \Exception(ERROR_MSG_DD_NAME . $this->getInstructionIdentification());
        }
        $this->DebtorName = $name;
        return $this;
    }

    /**
     * @param $debtorCountry
     * @return $this
     */
    public function setDebtorCountry($debtorCountry)
    {
        $this->debtorCountry = $debtorCountry;

        return $this;
    }

    /**
     * @param $debtorAddressLine1
     * @return $this
     */
    public function setDebtorAddressLine1($debtorAddressLine1)
    {
        $this->debtorAddressLine1 = $debtorAddressLine1;

        return $this;
    }

    /**
     * @param $debtorAddressLine2
     * @return $this
     */
    public function setDebtorAddressLine2($debtorAddressLine2)
    {
        $this->debtorAddressLine2 = $debtorAddressLine2;

        return $this;
    }

    /**
     * @param $debtorOrganizationIdentification
     * @return $this
     */
    public function setDebtorOrganizationIdentification($debtorOrganizationIdentification)
    {
        $this->debtorOrganizationIdentification = $debtorOrganizationIdentification;

        return $this;
    }

    /**
     * @param $debtorPrivateIdentification
     * @return $this
     */
    public function setDebtorPrivateIdentification($debtorPrivateIdentification)
    {
        $this->debtorPrivateIdentification = $debtorPrivateIdentification;

        return $this;
    }

    /**
     * Direct Debit IBAN
     * max  34 length
     *
     * @param $IBAN
     * @return $this
     * @throws \Exception
     */
    public function setDebitIBAN($IBAN)
    {
        $IBAN = $this->removeSpaces($IBAN);

        if (!$this->checkIBAN($IBAN)) {
            throw new \Exception(ERROR_MSG_DD_IBAN . $this->getInstructionIdentification());
        }
        $this->IBAN = $IBAN;
        return $this;
    }

    /**
     * Direct Debit Invoice
     *
     * @param $invoice
     * @return $this
     * @throws \Exception
     */
    public function setDirectDebitInvoice($invoice)
    {
        $invoice = $this->unicodeDecode($invoice);

        if (!$this->checkStringLength($invoice, 140)) {
            throw new \Exception(ERROR_MSG_DD_INVOICE_NUMBER . $this->getInstructionIdentification());
        }
        $this->directDebitInvoice = $invoice;
        return $this;
    }

    /**
     * Amount of money to be moved between the debtor and creditor, before deduction of charges, expressed in the
     * currency as ordered by the initiating party.
     *
     * @return float
     */
    public function getInstructedAmount()
    {
        return $this->InstructedAmount;
    }

    /**
     * @return bool
     */
    public function checkIsValidTransaction()
    {
        if (!$this->getIBAN() || !$this->getDirectDebitInvoice() || !$this->getDebtorName()) {
            return false;
        }
        return true;
    }

    /**
     * @return \SimpleXMLElement
     */
    public function getSimpleXMLElementTransaction()
    {
        //Direct Debit Transaction data
        $directDebitTransactionInformation = new \SimpleXMLElement('<DrctDbtTxInf></DrctDbtTxInf>');
        $paymentIdentification = $directDebitTransactionInformation->addChild('PmtId');

        if ($this->getInstructionIdentification()) {
            $paymentIdentification->addChild('InstrId', $this->getInstructionIdentification());
        }

        $paymentIdentification->addChild('EndToEndId', $this->getEndToEndIdentification());

        $directDebitTransactionInformation->addChild('InstdAmt', $this->getInstructedAmount())
            ->addAttribute('Ccy', $this->getCurrency());

        $directDebitTransaction = $directDebitTransactionInformation->addChild('DrctDbtTx');
        $mandateRelatedInformation = $directDebitTransaction->addChild('MndtRltdInf');
        $mandateRelatedInformation->addChild('MndtId', $this->getMandateIdentification());
        $mandateRelatedInformation->addChild('DtOfSgntr', $this->getDateOfSignature());

        if ($this->getElectronicSignature()) {
            $mandateRelatedInformation->addChild('ElctrncSgntr', $this->getElectronicSignature());
        }

        if ($this->getBIC()) {
            $debtorAgent = $directDebitTransactionInformation->addChild('DbtrAgt')
                ->addChild('FinInstnId');
            $debtorAgent->addChild('BIC', $this->getBIC());
        } else {
            $debtorAgent = $directDebitTransactionInformation->addChild('DbtrAgt')
                ->addChild('FinInstnId')->addChild('Othr');
            $debtorAgent->addChild('Id', self::BIC_NOTPROVIDED);
        }

        $debtor = $directDebitTransactionInformation->addChild('Dbtr');
        $debtor->addChild('Nm', $this->getDebtorName());

        if ($this->getDebtorCountry() && $this->getDebtorAddressLine1()) {
            $address = $debtor->addChild('PstlAdr');
            $address->addChild('Ctry', $this->getDebtorCountry());
            $address->addChild('AdrLine', $this->getDebtorAddressLine1());
            if ($this->getDebtorAddressLine2()) {
                $address->addChild('AdrLine', $this->getDebtorAddressLine2());
            }
        }

        if ($this->getDebtorOrganizationIdentification()) {
            $debtor_id = $debtor->addChild('Id');
            $concrete_id = $debtor_id->addChild('OrgId');
            $other = $concrete_id->addChild('Othr');
            $other->addChild('Id', $this->getDebtorOrganizationIdentification());
        } elseif ($this->getDebtorPrivateIdentification()) {
            $debtor_id = $debtor->addChild('Id');
            $concrete_id = $debtor_id->addChild('PrvtId');
            $other = $concrete_id->addChild('Othr');
            $other->addChild('Id', $this->getDebtorPrivateIdentification());
        }

        $directDebitTransactionInformation->addChild('DbtrAcct')
            ->addChild('Id')
            ->addChild('IBAN', $this->getIBAN());
        $directDebitTransactionInformation->addChild('RmtInf')
            ->addChild('Ustrd', $this->getDirectDebitInvoice());

        return $directDebitTransactionInformation;
    }
}

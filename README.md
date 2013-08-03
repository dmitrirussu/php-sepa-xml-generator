SEPA XML Generator v.0.1
====
SEPA Direct Debit Core Scheme (SDD Core) 
====

Single Euro Payments Area (SEPA)

The Single Euro Payments Area (SEPA) is a payment-integration initiative of the European Union for simplification of bank
transfers denominated in euro. As of March 2012, SEPA consists of the 28 EU member states, the four members of the EFTA
(Iceland, Liechtenstein, Norway and Switzerland) and Monaco.

Example of using.
====
        $SEPA = new SepaXmlFile();

        $SEPA::$_XML_FILES_REPOSITORY = '/sepa/xml_files/';
        $SEPA::$_FILE_NAME = 'sepa_test.xml';

        //Simple Example of Sepa Xml File Messages
        $SEPA::$_MESSAGES = array(
            array('message_id' => 123,
                'group_header' => array(
                    'company_name' => 'Amazing SRL ȘȚțș ыаывпавпва '
                ),
                'payment_info' => array(
                    'FRST' => array(
                        'id' => 1,
                        'creditor_iban' => 'MD24 AG00 0225 1000 1310 4168',
                        'creditor_bic' => 'AABAFI42',
                        'creditor_name' => 'Amazing SRL',
                        'scheme_identifier' => 'FR07ZZZ519993',
                        'transactions' => array(
                            array(
                                'id' => 1,
                                'endId' => 2,
                                'company_name' => 'Roy SRL',
                                'amount' => 100.4,
                                'umr' => 'SDD000000016PFX0713',
                                'iban' => 'FR14 2004 1010 0505 0001 3M02 606',
                                'bic' => 'AABAFI22',
                                'mandate_sign_date' => '2013-08-03',
                                'invoice' => 122
                            ),
                            array(
                                'id' => 3,
                                'endId' => 3,
                                'company_name' => 'Toy SRL',
                                'amount' => 10.4,
                                'umr' => 'SDD000000016PFX0714',
                                'iban' => 'FR14 2004 1010 0505 0001 3M02 606',
                                'bic' => 'AABAFI42',
                                'mandate_sign_date' => '2013-08-03',
                                'invoice' => 1223
                            )
                        )
                    ))));

            $SEPA->export()->viewXmlFile();



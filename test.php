<?php
/**
 * Created by Dumitru Russu.
 * Date: 7/15/13
 * Time: 10:14 PM
 * To change this template use File | Settings | File Templates.
 */
function __autoload($fileName) {
	require_once $fileName . '.php';
}
header ("Content-Type:text/xml");

$SEPA = new SepaXmlFile();
$SEPA->export();

<?php
/**
 * Created by Dumitru Russu. Email: <dmitri.russu@gmail.com>
 * Date: 04.12.2014
 * Time: 15:47
 * SEPA${NAME} 
 */

namespace SEPA;


interface TransactionInterface {
	public function checkIsValidTransaction();
	public function getSimpleXMLElementTransaction();
}
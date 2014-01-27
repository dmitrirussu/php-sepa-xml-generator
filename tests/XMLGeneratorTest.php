<?php

class XmlGeneratorTest extends PHPUnit_Framework_TestCase {
	public function testOne()
	{
		$xmlGenerator = new \SEPA\XMLGenerator();

		$this->assertTrue($xmlGenerator instanceof \SEPA\XMLGenerator);
	}
}

?>
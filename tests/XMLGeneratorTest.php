<?php

class XmlGeneratorTest extends \PHPUnit\Framework\TestCase
{
    public function testOne()
    {
        $xmlGenerator = new \SEPA\XMLGenerator();

        $this->assertTrue($xmlGenerator instanceof \SEPA\XMLGenerator);
    }
}

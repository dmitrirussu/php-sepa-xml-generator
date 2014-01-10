<?php

namespace SEPA;

if (!\is_file(__DIR__.'/vendor/autoload.php')) {
	throw new \LogicException('Please: ./composer.phar install --dev');
}

require_once(__DIR__.'/vendor/autoload.php');
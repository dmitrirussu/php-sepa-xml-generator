<?php

namespace SEPA;

/**
 * Interface XML Generator
 * Class XMLGeneratorInterface
 *
 * @package SEPA
 */
interface XMLGeneratorInterface
{
    public function addXmlMessage(Message $message);

    public function getGeneratedXml();

    public function save($fileName);

    public function view();
}

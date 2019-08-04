<?php

namespace Horlyk\Bundle\FetchXmlBundle\Services;

interface QueryBuilderInterface
{
    public function getQuery(): string;

    public function getCountQuery(): string;
}

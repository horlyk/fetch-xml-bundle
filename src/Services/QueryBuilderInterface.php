<?php

namespace Horlyk\Bundle\FetchXmlBundle\Services;

interface QueryBuilderInterface
{
    public function getQuery(): string;

    /**
     * @deprecated This method is deprecated and will be removed in 1.4.0. Use ...->isCountQuery(true) instead.
     */
    public function getCountQuery(): string;
}

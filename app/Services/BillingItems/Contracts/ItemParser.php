<?php

namespace App\Services\BillingItems\Contracts;

interface ItemParser
{
    /**
     * Parse the billing items.
     *
     * @param array $billingItems
     * @return array
     */
    public function parse(array $billingItems): array;
}
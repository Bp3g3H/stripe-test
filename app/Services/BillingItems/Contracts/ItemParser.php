<?php

namespace App\Services\BillingItems\Contracts;

interface ItemParser
{
    /**
     * Parse the billing items.
     */
    public function parse(array $billingItems): array;
}

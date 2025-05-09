<?php

namespace App\Services\Payment\Contracts;

interface Payable
{
    public function pay($identifier);

    public function set(array $billingItems);
}

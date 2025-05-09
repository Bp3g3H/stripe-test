<?php

namespace App\Services\Payment;

use App\Services\Payment\Contracts\Payable;

class PayingService
{
    protected Payable $payable;

    public function __construct(Payable $payable)
    {
        $this->payable = $payable;
    }

    public function pay($billingItems, $identifier)
    {
        $this->payable->set($billingItems);

        return $this->payable->pay($identifier);
    }
}

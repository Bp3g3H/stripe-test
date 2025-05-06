<?php

namespace App\Http\Controllers;

use App\Services\BillingItems\BillingItemsService;
use App\Services\BillingItems\ProductItemParser;
use App\Services\BillingItems\ProductItemProvider;
use App\Services\Payment\PayingService;
use App\Services\Payment\StripePayment;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    private BillingItemsService $billingItemsService;
    private PayingService $payingService;

    public function __construct(BillingItemsService $billingItemsService, PayingService $payingService)
    {
        $this->billingItemsService = $billingItemsService;
        $this->payingService = $payingService;
    }

    public function pay()
    {
        // Get parsed billing items and identifier
        $billingItems = $this->billingItemsService->getParsedBillingItems();
        $identifier = $this->billingItemsService->getIdentifier();

        // Process payment and get the checkout URL
        $checkoutUrl = $this->payingService->pay($billingItems, $identifier);

        return response()->json(['checkout_url' => $checkoutUrl]);
    }

    public function success()
    {
        return "Payment was successful!";
    }

    public function cancel()
    {
        return "Payment was cancelled!";
    }
}

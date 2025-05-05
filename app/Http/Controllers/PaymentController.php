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
    public function pay() 
    {
        $productItemProvider = new ProductItemProvider();
        $prodcutItemParser = new ProductItemParser();
        $billingItems = new BillingItemsService($prodcutItemParser, $productItemProvider, Auth::id());
        $stripePayment = new StripePayment();
        $paymentService = new PayingService($stripePayment);
        $checkoutUrl = $paymentService->pay($billingItems->getParsedBillingItems(), $billingItems->getIdentifier());
        return $checkoutUrl;
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

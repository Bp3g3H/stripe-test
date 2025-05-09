<?php

namespace App\Services\BillingItems;

use App\Services\BillingItems\Contracts\ItemParser;

class StripeItemsParser implements ItemParser
{
    /**
     * Parse the billing items.
     */
    public function parse(array $billingItems): array
    {
        $parsedItems = [];

        foreach ($billingItems as $item) {
            $parsedItems[] = [
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => $item['name'],
                    ],
                    'unit_amount' => $item['price'] * 100, // Convert to cents
                ],
                'quantity' => $item['quantity'],
            ];
        }

        return $parsedItems;
    }
}

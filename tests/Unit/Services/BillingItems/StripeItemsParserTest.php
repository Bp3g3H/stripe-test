<?php

namespace Tests\Unit\Services\BillingItems;

use App\Services\BillingItems\StripeItemsParser;
use Mockery;
use PHPUnit\Framework\TestCase;

class StripeItemsParserTest extends TestCase
{
    public function test_parse_billing_items()
    {
        // Arrange: Create an instance of ProductItemParser
        $parser = new StripeItemsParser();

        // Input billing items
        $billingItems = [
            [
                'name' => 'Test Product 1',
                'price' => 10.00, // Price in dollars
                'quantity' => 2,
            ],
            [
                'name' => 'Test Product 2',
                'price' => 20.50, // Price in dollars
                'quantity' => 1,
            ],
        ];

        // Expected parsed items
        $expectedParsedItems = [
            [
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => 'Test Product 1',
                    ],
                    'unit_amount' => 1000, // Price in cents
                ],
                'quantity' => 2,
            ],
            [
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => 'Test Product 2',
                    ],
                    'unit_amount' => 2050, // Price in cents
                ],
                'quantity' => 1,
            ],
        ];

        // Act: Call the parse method
        $parsedItems = $parser->parse($billingItems);

        // Assert: Verify the parsed items match the expected output
        $this->assertEquals($expectedParsedItems, $parsedItems);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
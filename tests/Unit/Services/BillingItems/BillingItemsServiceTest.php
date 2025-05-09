<?php

namespace Tests\Unit\Services\BillingItems;

use App\Services\BillingItems\BillingItemsService;
use App\Services\BillingItems\Contracts\ItemParser;
use App\Services\BillingItems\Contracts\ItemProvider;
use Mockery;
use PHPUnit\Framework\TestCase;

class BillingItemsServiceTest extends TestCase
{
    public function test_get_parsed_billing_items()
    {
        // Mock the ItemProvider
        $mockItemProvider = $this->createMock(ItemProvider::class);
        $mockItemProvider->method('get')->willReturn([
            ['name' => 'Test Product', 'price' => 1000, 'quantity' => 1],
        ]);
        $mockItemProvider->method('getIdentifier')->willReturn('test-identifier');

        // Mock the ItemParser
        $mockItemParser = $this->createMock(ItemParser::class);
        $mockItemParser->method('parse')->willReturn([
            [
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => ['name' => 'Test Product'],
                    'unit_amount' => 1000,
                ],
                'quantity' => 1,
            ],
        ]);

        // Instantiate the BillingItemsService with mocks
        $billingItemsService = new BillingItemsService($mockItemParser, $mockItemProvider, 1);

        // Assert the identifier is correct
        $this->assertEquals('test-identifier', $billingItemsService->getIdentifier());

        // Assert the parsed billing items are correct
        $this->assertEquals([
            [
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => ['name' => 'Test Product'],
                    'unit_amount' => 1000,
                ],
                'quantity' => 1,
            ],
        ], $billingItemsService->getParsedBillingItems());
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
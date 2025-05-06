<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\BillingItems\BillingItemsService;
use App\Services\Payment\PayingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PaymentControllerTest extends TestCase
{
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a user and authenticate for all tests
        $this->user = User::factory()->create();
    }

    public function test_pay_returns_checkout_url()
    {
        $mockBillingItemsService = $this->createMock(BillingItemsService::class);
        $mockBillingItemsService->method('getParsedBillingItems')->willReturn([
            [
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => ['name' => 'Test Product'],
                    'unit_amount' => 1000,
                ],
                'quantity' => 1,
            ],
        ]);
        $mockBillingItemsService->method('getIdentifier')->willReturn('test-identifier');
    
        $mockPayingService = $this->createMock(PayingService::class);
        $mockPayingService->method('pay')->willReturn('https://stripe.com/checkout/test-session');
    
        $this->app->instance(BillingItemsService::class, $mockBillingItemsService);
        $this->app->instance(PayingService::class, $mockPayingService);
    
        $response = $this->actingAs($this->user)->postJson(route('payment.pay'));
    
        $response->assertStatus(200)
                 ->assertJson(['checkout_url' => 'https://stripe.com/checkout/test-session']);
    }
}

<?php

namespace Tests\Unit\Services\BillingItems;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Repositories\CartRepository;
use App\Services\BillingItems\CartItemsProvider;
use Mockery;
use Tests\TestCase;

class CartItemsProviderTest extends TestCase
{
    public function test_get_returns_cart_items_with_identifier()
    {
        // Arrange: Mock the CartRepository
        $userId = 1;

        $product = new Product(['name' => 'Test Product']);
        $cartItem = new CartItem(['quantity' => 1, 'price' => 100, 'total_sum' => 100]);
        $cartItem->setRelation('product', $product);

        $cart = new Cart(['id' => 123, 'user_id' => $userId]);
        $cart->setRelation('items', collect([$cartItem]));

        $cartRepositoryMock = Mockery::mock(CartRepository::class);
        $cartRepositoryMock->shouldReceive('getCartWithStatusPending')
            ->with($userId)
            ->andReturn($cart);

        // Act: Inject the mocked repository into the provider
        $provider = new CartItemsProvider($cartRepositoryMock);

        $result = $provider->get($userId);

        // Assert: Verify the results
        $this->assertCount(1, $result);
        $this->assertEquals('Test Product', $result[0]['name']);
        $this->assertEquals(123, $provider->getIdentifier());
    }

    public function test_get_returns_empty_array_when_no_cart_exists()
    {
        // Arrange: Mock the CartRepository to return null
        $userId = 1;

        $cartRepositoryMock = Mockery::mock(CartRepository::class);
        $cartRepositoryMock->shouldReceive('getCartWithStatusPending')
            ->with($userId)
            ->andReturn(null);

        // Act: Inject the mocked repository into the provider
        $provider = new CartItemsProvider($cartRepositoryMock);

        $result = $provider->get($userId);

        // Assert: Verify the results
        $this->assertEmpty($result);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}

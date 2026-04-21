<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Cart;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartCheckoutTest extends TestCase
{
    use RefreshDatabase;

    protected function createCartWithOneItem(User $user): Cart
    {
        $brand = Brand::create([
            'name' => 'Nike',
            'description' => 'Performance brand',
            'accent_color' => '#000000',
        ]);

        $product = Product::create([
            'brand_id' => $brand->id,
            'name' => 'Pegasus 41',
            'description' => 'Daily trainer',
            'price' => 6995,
            'stock' => 5,
            'category' => 'footwear',
            'is_featured' => false,
            'is_archived' => false,
        ]);

        $cart = Cart::create(['user_id' => $user->id]);

        $cart->items()->create([
            'product_id' => $product->id,
            'quantity' => 1,
            'price' => $product->price,
        ]);

        return $cart;
    }

    public function test_checkout_requires_delivery_and_payment_fields(): void
    {
        $user = User::factory()->create(['role' => 'user']);
        $this->createCartWithOneItem($user);

        $response = $this->actingAs($user)->post(route('cart.checkout'));

        $response->assertSessionHasErrors(['delivery_window', 'payment_method']);
    }

    public function test_successful_checkout_redirects_to_confirmation_page(): void
    {
        $user = User::factory()->create(['role' => 'user']);
        $this->createCartWithOneItem($user);

        $response = $this->actingAs($user)->post(route('cart.checkout'), [
            'recipient_name' => 'Jane Doe',
            'phone' => '09171234567',
            'address_line1' => '123 Main Street',
            'address_line2' => 'Unit 4B',
            'city' => 'Makati',
            'province' => 'Metro Manila',
            'postal_code' => '1223',
            'country' => 'Philippines',
            'delivery_window' => 'standard',
            'payment_method' => 'card',
        ]);

        $response->assertRedirect(route('cart.confirmation', absolute: false));
    }

    public function test_checkout_stores_confirmation_payload_in_session(): void
    {
        $user = User::factory()->create(['role' => 'user']);
        $this->createCartWithOneItem($user);

        $response = $this->actingAs($user)->post(route('cart.checkout'), [
            'recipient_name' => 'Jane Doe',
            'phone' => '09171234567',
            'address_line1' => '123 Main Street',
            'address_line2' => null,
            'city' => 'Makati',
            'province' => 'Metro Manila',
            'postal_code' => '1223',
            'country' => 'Philippines',
            'delivery_window' => 'express',
            'payment_method' => 'gcash',
        ]);

        $response->assertSessionHas('checkout_confirmation');

        $confirmation = session('checkout_confirmation');

        $this->assertIsArray($confirmation);
        $this->assertArrayHasKey('order_number', $confirmation);
        $this->assertArrayHasKey('delivery_window', $confirmation);
        $this->assertArrayHasKey('payment_method', $confirmation);
        $this->assertArrayHasKey('items', $confirmation);
    }
}

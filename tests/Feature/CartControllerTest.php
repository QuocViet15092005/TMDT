<?php

namespace Tests\Feature;

use Tests\TestCase;

class CartControllerTest extends TestCase
{
    public function test_cart_index_handles_missing_product_data_gracefully(): void
    {
        session(['cart' => [
            1 => [
                'name' => 'Áo thể thao',
                'quantity' => 2,
            ],
            2 => [
                'name' => 'Giày chạy',
                'price' => 250000,
                'quantity' => 1,
            ],
        ]]);

        $response = $this->get(route('cart.index'));

        $response->assertOk();
        $response->assertSee('Giỏ hàng của bạn');
    }
}

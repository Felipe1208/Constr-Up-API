<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_returns_only_data_array(): void
    {
        $this->createProduct(['product' => 'alpha']);
        $this->createProduct(['product' => 'beta']);

        $response = $this->getJson('/api/product');

        $response->assertOk();
        $response->assertJsonCount(2);
        $response->assertJsonMissing(['first_page_url']);
    }

    public function test_index_filters_by_product_and_description(): void
    {
        $this->createProduct(['product' => 'alpha drill', 'description' => 'premium steel']);
        $this->createProduct(['product' => 'beta hammer', 'description' => 'basic wood']);

        $response = $this->getJson('/api/product?product=alpha&description=steel');

        $response->assertOk();
        $response->assertJsonCount(1);
        $this->assertSame('alpha drill', $response->json('0.product'));
    }

    public function test_index_filters_by_price_and_id(): void
    {
        $first = $this->createProduct(['price' => '10.00']);
        $this->createProduct(['price' => '15.00']);

        $response = $this->getJson('/api/product?price=10.00&id=' . $first->id);

        $response->assertOk();
        $response->assertJsonCount(1);
        $this->assertSame($first->id, $response->json('0.id'));
    }

    public function test_index_orders_by_product_name(): void
    {
        $this->createProduct(['product' => 'gamma']);
        $this->createProduct(['product' => 'alpha']);
        $this->createProduct(['product' => 'beta']);

        $response = $this->getJson('/api/product?order_by=product&order_dir=asc');

        $response->assertOk();
        $this->assertSame(['alpha', 'beta', 'gamma'], array_column($response->json(), 'product'));
    }

    public function test_index_orders_by_price_desc(): void
    {
        $this->createProduct(['price' => '10.00']);
        $this->createProduct(['price' => '5.00']);
        $this->createProduct(['price' => '20.00']);

        $response = $this->getJson('/api/product?order_by=price&order_dir=desc');

        $response->assertOk();
        $this->assertSame(['20.00', '10.00', '5.00'], array_column($response->json(), 'price'));
    }

    public function test_find_returns_not_found_message_when_missing(): void
    {
        $response = $this->getJson('/api/product/9999');

        $response->assertStatus(404);
        $response->assertJson(['message' => 'Produto não encontrado.']);
    }

    public function test_find_returns_product_when_exists(): void
    {
        $product = $this->createProduct(['product' => 'alpha']);

        $response = $this->getJson('/api/product/' . $product->id);

        $response->assertOk();
        $this->assertSame($product->id, $response->json('id'));
    }

    public function test_update_returns_not_found_message_when_missing(): void
    {
        $response = $this->putJson('/api/product/9999', ['product' => 'updated']);

        $response->assertStatus(404);
        $response->assertJson(['message' => 'Produto não encontrado.']);
    }

    public function test_update_updates_existing_product(): void
    {
        $product = $this->createProduct(['product' => 'before']);

        $response = $this->putJson('/api/product/' . $product->id, [
            'product' => 'after',
        ]);

        $response->assertOk();
        $this->assertSame('after', $response->json('product'));
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'product' => 'after',
        ]);
    }

    public function test_delete_returns_not_found_message_when_missing(): void
    {
        $response = $this->deleteJson('/api/product/9999');

        $response->assertStatus(404);
        $response->assertJson(['message' => 'Produto não encontrado.']);
    }

    public function test_delete_returns_success_message_when_exists(): void
    {
        $product = $this->createProduct();

        $response = $this->deleteJson('/api/product/' . $product->id);

        $response->assertOk();
        $response->assertJson(['message' => 'Produto excluído com sucesso.']);
        $this->assertSoftDeleted('products', ['id' => $product->id]);
    }

    public function test_store_creates_product(): void
    {
        $payload = [
            'product' => 'alpha drill',
            'description' => 'premium steel',
            'price' => 10.5,
            'stock' => 100,
        ];

        $response = $this->postJson('/api/product', $payload);

        $response->assertCreated();
        $response->assertJsonFragment(['product' => 'alpha drill']);
        $this->assertDatabaseHas('products', [
            'product' => 'alpha drill',
            'stock' => 100,
        ]);
    }

    private function createProduct(array $attributes = []): Product
    {
        return Product::query()->create(array_merge([
            'product' => 'default product',
            'description' => 'default description',
            'price' => '10.00',
            'stock' => 10,
        ], $attributes));
    }
}

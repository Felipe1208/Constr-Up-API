<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Collection;

class ProductService
{
    public function __construct(private readonly Product $product) {}

    /**
     * @param  array<string, mixed>  $filters
     */
    public function index(
        array $filters = [],
        string $orderBy = 'product',
        string $orderDir = 'asc',
        int $perPage = 15
    ): Collection {
        $query = $this->product
            ->newQuery()
            ->orderBy($orderBy, $orderDir);

        foreach ($filters as $field => $value) {
            match ($field) {
                'product', 'brand', 'description' => $query->where($field, 'like', '%'.$value.'%'),
                default => $query->where($field, $value),
            };
        }

        return $query
            ->paginate($perPage)
            ->getCollection()
            ->values();
    }

    public function create(array $data): Product
    {
        return $this->product->newQuery()->create($data);
    }

    public function find(int $id): ?Product
    {
        return $this->product->newQuery()->find($id);
    }

    public function update(int $id, array $data): ?Product
    {
        $product = $this->find($id);
        if (! $product) {
            return null;
        }

        $product->update($data);

        return $product;
    }

    public function delete(int $id): bool
    {
        $product = $this->product->newQuery()->find($id);

        if (! $product) {
            return false;
        }

        $product->delete();

        return true;
    }
}

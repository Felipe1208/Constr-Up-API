<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductDeleteRequest;
use App\Http\Requests\ProductFindRequest;
use App\Http\Requests\ProductIndexRequest;
use App\Http\Requests\ProductStoreRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\Services\ProductService;
use Illuminate\Http\Response;

class ProductController extends Controller
{
    public function __construct(private readonly ProductService $service) {}

    public function index(ProductIndexRequest $request)
    {
        $perPage = $request->perPage();
        $filters = $request->filters();
        $sort = $request->sort();

        return response()->json(
            $this->service->index($filters, $sort['by'], $sort['dir'], $perPage)
        );
    }

    public function store(ProductStoreRequest $request)
    {
        $validated = $request->validated();

        $product = $this->service->create($validated);

        return response()->json($product, Response::HTTP_CREATED);
    }

    public function find(ProductFindRequest $request, string $id)
    {
        $product = $this->service->find((int) $id);

        if (! $product) {
            return response()->json(
                ['message' => 'Produto não encontrado.'],
                Response::HTTP_NOT_FOUND
            );
        }

        return response()->json($product);
    }

    public function update(ProductUpdateRequest $request, string $id)
    {
        $validated = $request->validated();

        $product = $this->service->update((int) $id, $validated);

        if (! $product) {
            return response()->json(
                ['message' => 'Produto não encontrado.'],
                Response::HTTP_NOT_FOUND
            );
        }

        return response()->json($product);
    }

    public function delete(ProductDeleteRequest $request, string $id)
    {
        $deleted = $this->service->delete((int) $id);

        if (! $deleted) {
            return response()->json(
                ['message' => 'Produto não encontrado.'],
                Response::HTTP_NOT_FOUND
            );
        }

        return response()->json(['message' => 'Produto excluído com sucesso.']);
    }
}

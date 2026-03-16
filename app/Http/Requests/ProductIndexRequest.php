<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;

class ProductIndexRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'per_page' => ['sometimes', 'integer', 'min:1', 'max:100'],
            'id' => ['sometimes', 'integer', 'min:1'],
            'product' => ['sometimes', 'string', 'max:255'],
            'description' => ['sometimes', 'string'],
            'price' => ['sometimes', 'numeric', 'min:0'],
            'order_by' => ['sometimes', 'string', 'in:product,price'],
            'order_dir' => ['sometimes', 'string', 'in:asc,desc'],
        ];
    }

    public function perPage(): int
    {
        return (int) $this->input('per_page', 15);
    }

    /**
     * @return array{by: string, dir: string}
     */
    public function sort(): array
    {
        $validated = $this->validated();

        return [
            'by' => $validated['order_by'] ?? 'product',
            'dir' => $validated['order_dir'] ?? 'asc',
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function filters(): array
    {
        $filters = Arr::only(
            $this->validated(),
            ['id', 'product', 'description', 'price']
        );

        return array_filter(
            $filters,
            static fn ($value) => $value !== null && $value !== ''
        );
    }

    protected function prepareForValidation(): void
    {
        if (! $this->has('per_page')) {
            return;
        }

        $perPage = (int) $this->input('per_page', 15);
        $perPage = max(1, min($perPage, 100));

        $this->merge([
            'per_page' => $perPage,
        ]);
    }
}

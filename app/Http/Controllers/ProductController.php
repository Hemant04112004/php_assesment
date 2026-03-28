<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        return view('products.index');
    }

    public function list(): JsonResponse
    {
        $products = $this->readProducts();

        usort($products, static function (array $a, array $b): int {
            return strcmp($a['datetime_submitted'], $b['datetime_submitted']);
        });

        $products = array_map(static function (array $product): array {
            $quantity = (int) $product['quantity_in_stock'];
            $price = (float) $product['price_per_item'];

            $product['total_value_number'] = round($quantity * $price, 2);

            return $product;
        }, $products);

        $grandTotal = array_reduce($products, static function (float $carry, array $product): float {
            return $carry + (float) $product['total_value_number'];
        }, 0.0);

        return response()->json([
            'products' => $products,
            'grand_total' => round($grandTotal, 2),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'product_name' => ['required', 'string', 'max:255'],
            'quantity_in_stock' => ['required', 'integer', 'min:0'],
            'price_per_item' => ['required', 'numeric', 'min:0'],
        ]);

        $products = $this->readProducts();

        $products[] = [
            'id' => (string) Str::uuid(),
            'product_name' => $validated['product_name'],
            'quantity_in_stock' => (int) $validated['quantity_in_stock'],
            'price_per_item' => round((float) $validated['price_per_item'], 2),
            'datetime_submitted' => now()->toDateTimeString(),
        ];

        $this->writeProducts($products);

        return response()->json([
            'message' => 'Product saved successfully.',
        ], 201);
    }

    public function update(Request $request, string $id): JsonResponse
    {
        $validated = $request->validate([
            'product_name' => ['required', 'string', 'max:255'],
            'quantity_in_stock' => ['required', 'integer', 'min:0'],
            'price_per_item' => ['required', 'numeric', 'min:0'],
        ]);

        $products = $this->readProducts();
        $found = false;

        foreach ($products as &$product) {
            if (($product['id'] ?? null) !== $id) {
                continue;
            }

            $product['product_name'] = $validated['product_name'];
            $product['quantity_in_stock'] = (int) $validated['quantity_in_stock'];
            $product['price_per_item'] = round((float) $validated['price_per_item'], 2);
            $found = true;
            break;
        }
        unset($product);

        if (! $found) {
            return response()->json([
                'message' => 'Product not found.',
            ], 404);
        }

        $this->writeProducts($products);

        return response()->json([
            'message' => 'Product updated successfully.',
        ]);
    }

    private function storageFilePath(): string
    {
        return storage_path('app/products.json');
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function readProducts(): array
    {
        $path = $this->storageFilePath();

        if (! File::exists($path)) {
            return [];
        }

        $content = File::get($path);
        if ($content === '') {
            return [];
        }

        $decoded = json_decode($content, true);

        return is_array($decoded) ? $decoded : [];
    }

    /**
     * @param array<int, array<string, mixed>> $products
     */
    private function writeProducts(array $products): void
    {
        $path = $this->storageFilePath();
        $directory = dirname($path);

        if (! File::isDirectory($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        File::put($path, json_encode($products, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES), true);
    }
}
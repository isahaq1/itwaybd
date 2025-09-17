<?php

namespace App\Repositories;

use App\Models\Sale;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class SaleRepository
{
    public function query(): Builder
    {
        return Sale::query()->with(['user', 'items.product']);
    }

    public function paginateWithFilters(array $filters, int $perPage = 15): LengthAwarePaginator
    {
        $query = $this->query();

        if ($customer = Arr::get($filters, 'customer')) {
            $query->whereHas('user', fn ($q) => $q->where('name', 'like', "%{$customer}%"));
        }

        if ($product = Arr::get($filters, 'product')) {
            $query->whereHas('items.product', fn ($q) => $q->where('name', 'like', "%{$product}%"));
        }

        if ($from = Arr::get($filters, 'from')) {
            $query->whereDate('sale_date', '>=', $from);
        }
        if ($to = Arr::get($filters, 'to')) {
            $query->whereDate('sale_date', '<=', $to);
        }

        return $query->latest('sale_date')->paginate($perPage)->withQueryString();
    }

    public function create(array $saleData, array $itemsData): Sale
    {
        return DB::transaction(function () use ($saleData, $itemsData) {
            $totals = calculateSaleTotal($itemsData);

            $sale = Sale::create(array_merge($saleData, $totals));

            foreach ($itemsData as $item) {
                $quantity = (int) ($item['quantity'] ?? 0);
                $price = (float) ($item['price'] ?? 0);
                $discount = (float) ($item['discount'] ?? 0);
                $lineTotal = max(0, ($quantity * $price) - $discount);

                $sale->items()->create([
                    'product_id' => $item['product_id'],
                    'quantity' => $quantity,
                    'price' => $price,
                    'discount' => $discount,
                    'line_total' => $lineTotal,
                ]);
            }

            return $sale->load(['user', 'items.product']);
        });
    }

    public function softDelete(Sale $sale): void
    {
        $sale->items()->delete();
        $sale->delete();
    }

    public function listTrashed(int $perPage = 15): LengthAwarePaginator
    {
        return Sale::onlyTrashed()->with(['user'])->latest('deleted_at')->paginate($perPage);
    }

    public function restoreById(int $id): ?Sale
    {
        $sale = Sale::onlyTrashed()->find($id);
        if (! $sale) {
            return null;
        }
        $sale->restore();
        $sale->items()->onlyTrashed()->restore();
        return $sale->fresh(['user']);
    }
}



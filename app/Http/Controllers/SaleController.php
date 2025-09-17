<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSaleRequest;
use App\Models\Product;
use App\Models\Sale;
use App\Models\User;
use App\Repositories\SaleRepository;
use App\Services\SaleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SaleController extends Controller
{
    public function __construct(
        private readonly SaleService $service
    ) {}

    public function index(Request $request): View
    {
        $filters = $request->only(['customer', 'product', 'from', 'to']);
        $sales = app(SaleRepository::class)->paginateWithFilters($filters, perPage: 15);

        return view('sales.index', compact('sales', 'filters'));
    }

    public function create(): View
    {
        $products = Product::orderBy('name')->get(['id', 'name', 'price']);
        $customers = User::orderBy('name')->get(['id', 'name']);
        return view('sales.create', compact('products', 'customers'));
    }

    public function store(StoreSaleRequest $request): JsonResponse
    {
        $sale = $this->service->createSale(
            saleData: $request->only(['user_id', 'sale_date', 'status', 'notes']),
            itemsData: $request->input('items', [])
        );

        return response()->json([
            'message' => 'Sale created successfully',
            'data' => $sale,
        ]);
    }

    public function destroy(Sale $sale): RedirectResponse
    {
        app(SaleRepository::class)->softDelete($sale);
        return back()->with('status', 'Sale moved to trash');
    }

    public function trash(Request $request): View
    {
        $trashed = app(SaleRepository::class)->listTrashed(perPage: 15);
        return view('sales.trash', compact('trashed'));
    }

    public function restore(int $id): RedirectResponse
    {
        $sale = app(SaleRepository::class)->restoreById($id);
        return back()->with('status', $sale ? 'Sale restored' : 'Sale not found');
    }
}

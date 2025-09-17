<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $totalSales = Sale::sum('grand_total');
        $todaySales = Sale::whereDate('sale_date', today())->sum('grand_total');
        $customersCount = User::count();
        $productsCount = Product::count();

        $recentSales = Sale::with('user')
            ->latest('sale_date')
            ->take(10)
            ->get();

        // Chart data for last 7 days
        $days = collect(range(0, 6))->map(fn ($i) => now()->subDays(6 - $i)->toDateString());
        $totalsByDate = Sale::selectRaw('sale_date, SUM(grand_total) as total')
            ->whereBetween('sale_date', [now()->subDays(6)->toDateString(), now()->toDateString()])
            ->groupBy('sale_date')
            ->pluck('total', 'sale_date');
        $chartLabels = $days->map(fn ($d) => \Carbon\Carbon::parse($d)->format('M d'));
        $chartValues = $days->map(fn ($d) => (float) ($totalsByDate[$d] ?? 0));

        return view('dashboard', compact(
            'totalSales', 'todaySales', 'customersCount', 'productsCount', 'recentSales', 'chartLabels', 'chartValues'
        ));
    }
}



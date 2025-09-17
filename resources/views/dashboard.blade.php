<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Dashboard</h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-white dark:bg-gray-800 p-4 rounded shadow">
                <div class="text-gray-500 text-sm">Total Sales</div>
                <div class="text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ number_format($totalSales, 2) }} BDT</div>
            </div>
            <div class="bg-white dark:bg-gray-800 p-4 rounded shadow">
                <div class="text-gray-500 text-sm">Today's Sales</div>
                <div class="text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ number_format($todaySales, 2) }} BDT</div>
            </div>
            <div class="bg-white dark:bg-gray-800 p-4 rounded shadow">
                <div class="text-gray-500 text-sm">Customers</div>
                <div class="text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ $customersCount }}</div>
            </div>
            <div class="bg-white dark:bg-gray-800 p-4 rounded shadow">
                <div class="text-gray-500 text-sm">Products</div>
                <div class="text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ $productsCount }}</div>
            </div>
        </div>

        <div class="mt-6 bg-white dark:bg-gray-800 p-4 rounded shadow">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-3">Sales (Last 7 Days)</h3>
            <canvas id="sales-chart" height="80" data-labels='@json($chartLabels ?? [])' data-values='@json($chartValues ?? [])'></canvas>
        </div>

        <div class="mt-6 bg-white dark:bg-gray-800 p-4 rounded shadow">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Recent Sales</h3>
                <a href="{{ route('sales.index') }}" class="text-sm text-blue-600">View all</a>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th class="px-3 py-2 text-left">Date</th>
                            <th class="px-3 py-2 text-left">Customer</th>
                            <th class="px-3 py-2 text-left">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($recentSales as $sale)
                            <tr>
                                <td class="px-3 py-2">{{ $sale->sale_date->format('Y-m-d') }}</td>
                                <td class="px-3 py-2">{{ $sale->user->name }}</td>
                                <td class="px-3 py-2">{{ number_format($sale->grand_total, 2) }} BDT</td>
                            </tr>
                        @empty
                            <tr>
                                <td class="px-3 py-4 text-center text-gray-500" colspan="3">No sales yet</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const el = document.getElementById('sales-chart');
            if (!el) return;
            const labels = JSON.parse(el.dataset.labels || '[]');
            const values = JSON.parse(el.dataset.values || '[]');
            new Chart(el, {
                type: 'line',
                data: {
                    labels,
                    datasets: [{
                        label: 'Sales (BDT)',
                        data: values,
                        borderColor: '#2563eb',
                        backgroundColor: 'rgba(37, 99, 235, 0.1)',
                        tension: 0.3,
                        fill: true,
                    }]
                },
                options: {
                    plugins: { legend: { display: false } },
                    scales: { y: { beginAtZero: true } }
                }
            });
        });
    </script>
    @endpush
</x-app-layout>

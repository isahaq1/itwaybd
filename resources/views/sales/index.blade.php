<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Sales
        </h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg p-6">
            <form method="GET" action="{{ route('sales.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Customer</label>
                    <input class="border border-gray-300 rounded-lg p-2.5 w-full focus:ring-cyan-500 focus:border-cyan-500" name="customer" placeholder="Search by customer" value="{{ $filters['customer'] ?? '' }}" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Product</label>
                    <input class="border border-gray-300 rounded-lg p-2.5 w-full focus:ring-cyan-500 focus:border-cyan-500" name="product" placeholder="Search by product" value="{{ $filters['product'] ?? '' }}" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">From Date</label>
                    <input class="border border-gray-300 rounded-lg p-2.5 w-full focus:ring-cyan-500 focus:border-cyan-500" type="date" name="from" value="{{ $filters['from'] ?? '' }}" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">To Date</label>
                    <input class="border border-gray-300 rounded-lg p-2.5 w-full focus:ring-cyan-500 focus:border-cyan-500" type="date" name="to" value="{{ $filters['to'] ?? '' }}" />
                </div>
                <div class="md:col-span-4 flex items-center space-x-3 mt-2">
                    <button class="bg-cyan-600 hover:bg-cyan-700 text-white rounded-lg px-4 py-2.5 transition-colors duration-200 shadow-sm flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                        </svg>
                        Filter
                    </button>
                    <a href="{{ route('sales.create') }}" class="bg-green-600 hover:bg-green-700 text-white rounded-lg px-4 py-2.5 transition-colors duration-200 shadow-sm flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        New Sale
                    </a>
                </div>
            </form>

            <div class="overflow-x-auto rounded-lg border border-gray-200">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @php($pageTotal = 0)
                        @foreach($sales as $sale)
                            @php($pageTotal += (float) $sale->grand_total)
                            <tr class="hover:bg-gray-50 transition-colors duration-150">
                                <td class="px-4 py-3">{{ $sale->sale_date->format('Y-m-d') }}</td>
                                <td class="px-4 py-3">{{ $sale->user->name }}</td>
                                <td class="px-4 py-3 font-medium">{{ $sale->grand_total_formatted }}</td>
                                <td class="px-4 py-3">
                                    <form method="POST" action="{{ route('sales.destroy', $sale) }}" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button class="text-red-600 hover:text-red-800 transition-colors duration-200 flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                            Trash
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">{{ $sales->links() }}</div>
            <div class="mt-2 font-semibold text-gray-700">Page total: <span class="text-cyan-600">{{ number_format($pageTotal, 2) }} BDT</span></div>

            <div class="mt-6">
                <a class="text-cyan-600 hover:text-cyan-800 transition-colors duration-200 flex items-center w-max" href="{{ route('sales.trash') }}">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                    View Trash
                </a>
            </div>
        </div>
    </div>
</x-app-layout>



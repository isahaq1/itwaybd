<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Sales
        </h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            <form method="GET" action="{{ route('sales.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <input class="border rounded p-2" name="customer" placeholder="Customer" value="{{ $filters['customer'] ?? '' }}" />
                <input class="border rounded p-2" name="product" placeholder="Product" value="{{ $filters['product'] ?? '' }}" />
                <input class="border rounded p-2" type="date" name="from" value="{{ $filters['from'] ?? '' }}" />
                <input class="border rounded p-2" type="date" name="to" value="{{ $filters['to'] ?? '' }}" />
                <div class="md:col-span-4">
                    <button class="bg-blue-600 text-white rounded px-4 py-2">Filter</button>
                    <a href="{{ route('sales.create') }}" class="ml-2 bg-green-600 text-white rounded px-4 py-2">New Sale</a>
                </div>
            </form>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th class="px-3 py-2 text-left">Date</th>
                            <th class="px-3 py-2 text-left">Customer</th>
                            <th class="px-3 py-2 text-left">Total</th>
                            <th class="px-3 py-2 text-left">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @php($pageTotal = 0)
                        @foreach($sales as $sale)
                            @php($pageTotal += (float) $sale->grand_total)
                            <tr>
                                <td class="px-3 py-2">{{ $sale->sale_date->format('Y-m-d') }}</td>
                                <td class="px-3 py-2">{{ $sale->user->name }}</td>
                                <td class="px-3 py-2">{{ $sale->grand_total_formatted }}</td>
                                <td class="px-3 py-2">
                                    <form method="POST" action="{{ route('sales.destroy', $sale) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button class="text-red-600">Trash</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">{{ $sales->links() }}</div>
            <div class="mt-2 font-semibold">Page total: {{ number_format($pageTotal, 2) }} BDT</div>

            <div class="mt-6">
                <a class="text-gray-700 underline" href="{{ route('sales.trash') }}">Trash</a>
            </div>
        </div>
    </div>
</x-app-layout>



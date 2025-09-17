<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Trashed Sales</h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            <a href="{{ route('sales.index') }}" class="text-gray-700 underline">Back to Sales</a>
            <div class="overflow-x-auto mt-4">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th class="px-3 py-2 text-left">Deleted At</th>
                            <th class="px-3 py-2 text-left">Customer</th>
                            <th class="px-3 py-2 text-left">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($trashed as $sale)
                            <tr>
                                <td class="px-3 py-2">{{ $sale->deleted_at }}</td>
                                <td class="px-3 py-2">{{ $sale->user->name ?? '-' }}</td>
                                <td class="px-3 py-2">
                                    <form method="POST" action="{{ route('sales.restore', $sale->id) }}">
                                        @csrf
                                        <button class="text-green-700">Restore</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $trashed->links() }}</div>
        </div>
    </div>
</x-app-layout>



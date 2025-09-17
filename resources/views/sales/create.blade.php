<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">New Sale</h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg p-6">
            <form id="sale-form" data-submit-url="{{ route('sales.store') }}" data-index-url="{{ route('sales.index') }}">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="relative">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Customer</label>
                        <select name="user_id" class="border border-gray-300 rounded-lg p-2.5 w-full focus:ring-cyan-500 focus:border-cyan-500">
                            <option value="">Select Customer</option>
                            @foreach($customers as $c)
                                <option value="{{ $c->id }}">{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="relative">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                        <input type="date" name="sale_date" class="border border-gray-300 rounded-lg p-2.5 w-full focus:ring-cyan-500 focus:border-cyan-500" value="{{ now()->toDateString() }}" />
                    </div>
                    <div class="relative">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <input type="text" name="status" class="border border-gray-300 rounded-lg p-2.5 w-full focus:ring-cyan-500 focus:border-cyan-500" value="draft" />
                    </div>
                </div>

                <div class="mt-8">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">Items</h3>
                        <button id="add-item" type="button" class="bg-cyan-600 hover:bg-cyan-700 text-white rounded-lg px-4 py-2 transition-colors duration-200 flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Add Item
                        </button>
                    </div>
                    <div class="overflow-x-auto rounded-lg border border-gray-200">
                        <table class="min-w-full divide-y divide-gray-200" id="items-table">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Qty</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Discount</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Line Total</th>
                                    <th class="px-4 py-3"></th>
                                </tr>
                            </thead>
                            <tbody id="items-body" class="bg-white divide-y divide-gray-200"></tbody>
                        </table>
                    </div>
                </div>

                <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium">Notes</label>
                        <textarea name="notes" class="border rounded p-2 w-full" rows="3"></textarea>
                    </div>
                    <div>
                        <div class="flex justify-between py-1"><span>Subtotal</span><span id="subtotal">0.00</span></div>
                        <div class="flex justify-between py-1"><span>Discount</span><span id="discount_total">0.00</span></div>
                        <div class="flex justify-between py-1 font-semibold"><span>Grand Total</span><span id="grand_total">0.00</span></div>
                    </div>
                </div>

                <div class="mt-8">
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white rounded-lg px-5 py-2.5 transition-colors duration-200 shadow-sm">Save Sale</button>
                    <a href="{{ route('sales.index') }}" class="ml-3 text-gray-700 hover:text-gray-900 font-medium">Cancel</a>
                    <span id="form-status" class="ml-3 text-sm"></span>
                </div>
            </form>
        </div>
    </div>

    <template id="item-row-template">
        <tr class="hover:bg-gray-50">
            <td class="px-4 py-3">
                <select class="product-select border border-gray-300 rounded-lg p-2 w-full focus:ring-cyan-500 focus:border-cyan-500">
                    <option value="">Select</option>
                    @foreach($products as $p)
                        <option data-price="{{ $p->price }}" value="{{ $p->id }}">{{ $p->name }}</option>
                    @endforeach
                </select>
            </td>
            <td class="px-4 py-3"><input type="number" min="1" value="1" class="qty-input border border-gray-300 rounded-lg p-2 w-20 focus:ring-cyan-500 focus:border-cyan-500" /></td>
            <td class="px-4 py-3"><input type="number" step="0.01" min="0" value="0" class="price-input border border-gray-300 rounded-lg p-2 w-28 focus:ring-cyan-500 focus:border-cyan-500" /></td>
            <td class="px-4 py-3"><input type="number" step="0.01" min="0" value="0" class="discount-input border border-gray-300 rounded-lg p-2 w-28 focus:ring-cyan-500 focus:border-cyan-500" /></td>
            <td class="px-4 py-3"><span class="line-total font-medium">0.00</span></td>
            <td class="px-4 py-3">
                <button type="button" class="remove-item text-red-600 hover:text-red-800 transition-colors duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                </button>
            </td>
        </tr>
    </template>

    @push('scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        function recalcTotals() {
            let items = [];
            let subtotal = 0, discount_total = 0, grand_total = 0;
            $('#items-body tr').each(function() {
                const tr = $(this);
                const product_id = tr.find('.product-select').val();
                const quantity = parseInt(tr.find('.qty-input').val() || 0);
                const price = parseFloat(tr.find('.price-input').val() || 0);
                const discount = parseFloat(tr.find('.discount-input').val() || 0);
                const line_total = Math.max(0, quantity * price - discount);
                tr.find('.line-total').text(line_total.toFixed(2));
                subtotal += quantity * price;
                discount_total += discount;
                grand_total += line_total;
                if (product_id) {
                    items.push({product_id, quantity, price, discount});
                }
            });
            $('#subtotal').text(subtotal.toFixed(2));
            $('#discount_total').text(discount_total.toFixed(2));
            $('#grand_total').text(grand_total.toFixed(2));
            return items;
        }

        function addItemRow() {
            const tpl = document.getElementById('item-row-template');
            const clone = tpl.content.cloneNode(true);
            $('#items-body').append(clone);
        }

        $(document).on('click', '#add-item', function() {
            addItemRow();
        });

        $(document).on('change keyup', '.product-select, .qty-input, .price-input, .discount-input', function() {
            const tr = $(this).closest('tr');
            if ($(this).hasClass('product-select')) {
                const price = parseFloat($(this).find(':selected').data('price') || 0);
                tr.find('.price-input').val(price.toFixed(2));
            }
            recalcTotals();
        });

        $(document).on('click', '.remove-item', function() {
            $(this).closest('tr').remove();
            recalcTotals();
        });

        $('#sale-form').on('submit', function(e) {
            e.preventDefault();
            const items = recalcTotals();
            const data = {
                _token: $('input[name=_token]').val(),
                user_id: $('select[name=user_id]').val(),
                sale_date: $('input[name=sale_date]').val(),
                status: $('input[name=status]').val(),
                notes: $('textarea[name=notes]').val(),
                items
            };
            $('#form-status').text('Saving...').removeClass('text-red-600');
            $.ajax({
                method: 'POST',
                url: $('#sale-form').data('submit-url'),
                data: data,
                success: function() {
                    $('#form-status').text('Saved');
                    window.location = $('#sale-form').data('index-url');
                },
                error: function(xhr) {
                    const msg = xhr.responseJSON?.message || 'Validation error';
                    $('#form-status').text(msg).addClass('text-red-600');
                }
            });
        });

        // seed one default row
        addItemRow();
    </script>
    @endpush
</x-app-layout>



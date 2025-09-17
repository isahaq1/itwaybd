<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">New Sale</h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            <form id="sale-form" data-submit-url="{{ route('sales.store') }}" data-index-url="{{ route('sales.index') }}">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium">Customer</label>
                        <select name="user_id" class="border rounded p-2 w-full">
                            <option value="">Select Customer</option>
                            @foreach($customers as $c)
                                <option value="{{ $c->id }}">{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium">Date</label>
                        <input type="date" name="sale_date" class="border rounded p-2 w-full" value="{{ now()->toDateString() }}" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium">Status</label>
                        <input type="text" name="status" class="border rounded p-2 w-full" value="draft" />
                    </div>
                </div>

                <div class="mt-6">
                    <div class="flex justify-between items-center mb-2">
                        <h3 class="font-semibold">Items</h3>
                        <button id="add-item" type="button" class="bg-blue-600 text-white rounded px-3 py-1">Add Item</button>
                    </div>
                    <table class="min-w-full divide-y divide-gray-200" id="items-table">
                        <thead>
                            <tr>
                                <th class="px-2 py-1 text-left">Product</th>
                                <th class="px-2 py-1 text-left">Qty</th>
                                <th class="px-2 py-1 text-left">Price</th>
                                <th class="px-2 py-1 text-left">Discount</th>
                                <th class="px-2 py-1 text-left">Line Total</th>
                                <th class="px-2 py-1"></th>
                            </tr>
                        </thead>
                        <tbody id="items-body"></tbody>
                    </table>
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

                <div class="mt-6">
                    <button type="submit" class="bg-green-600 text-white rounded px-4 py-2">Save</button>
                    <a href="{{ route('sales.index') }}" class="ml-2 text-gray-700">Cancel</a>
                    <span id="form-status" class="ml-3 text-sm"></span>
                </div>
            </form>
        </div>
    </div>

    <template id="item-row-template">
        <tr>
            <td class="px-2 py-1">
                <select class="product-select border rounded p-1 w-full">
                    <option value="">Select</option>
                    @foreach($products as $p)
                        <option data-price="{{ $p->price }}" value="{{ $p->id }}">{{ $p->name }}</option>
                    @endforeach
                </select>
            </td>
            <td class="px-2 py-1"><input type="number" min="1" value="1" class="qty-input border rounded p-1 w-20" /></td>
            <td class="px-2 py-1"><input type="number" step="0.01" min="0" value="0" class="price-input border rounded p-1 w-28" /></td>
            <td class="px-2 py-1"><input type="number" step="0.01" min="0" value="0" class="discount-input border rounded p-1 w-28" /></td>
            <td class="px-2 py-1"><span class="line-total">0.00</span></td>
            <td class="px-2 py-1"><button type="button" class="remove-item text-red-600">Remove</button></td>
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



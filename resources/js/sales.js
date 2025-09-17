import $ from 'jquery';

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

function initSaleCreatePage() {
    if (!document.getElementById('sale-form')) {
        return;
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
}

$(document).ready(function() {
    initSaleCreatePage();
});



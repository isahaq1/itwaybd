<?php

if (! function_exists('calculateSaleTotal')) {
    function calculateSaleTotal(array $items): array
    {
        $subtotal = 0.0;
        $discountTotal = 0.0;
        $grandTotal = 0.0;

        foreach ($items as $item) {
            $quantity = (int) ($item['quantity'] ?? 0);
            $price = (float) ($item['price'] ?? 0);
            $discount = (float) ($item['discount'] ?? 0);
            $lineTotal = max(0, ($quantity * $price) - $discount);
            $subtotal += ($quantity * $price);
            $discountTotal += $discount;
            $grandTotal += $lineTotal;
        }

        return [
            'subtotal' => round($subtotal, 2),
            'discount_total' => round($discountTotal, 2),
            'grand_total' => round($grandTotal, 2),
        ];
    }
}



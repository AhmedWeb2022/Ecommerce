<?php

function calculateTotalAmount(array $data)
{
    $totalAmount = 0;
    foreach ($data as $key => $value) {
        $subtotal = $value['quantity'] * $value['price'];
        $tax = $subtotal * 0.10; // 10% tax
        $totalAmount += $subtotal + $tax;
    }


    return $totalAmount;
}

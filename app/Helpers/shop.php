<?php

if (! function_exists('shop_money')) {
    function shop_money(float|int|string $amount): string
    {
        $symbol = config('shop.currency_symbol', 'Rs.');

        return $symbol.' '.number_format((float) $amount, 0);
    }
}

if (! function_exists('shop_name')) {
    function shop_name(): string
    {
        return config('shop.name', 'ElectroMart');
    }
}

<?php

return [
    'name' => env('SHOP_NAME', 'ElectroMart'),
    'tagline' => env('SHOP_TAGLINE', 'Your trusted local electronics & hardware store'),
    'currency' => env('SHOP_CURRENCY', 'PKR'),
    'currency_symbol' => env('SHOP_CURRENCY_SYMBOL', 'Rs.'),
    'tax_rate' => (float) env('SHOP_TAX_RATE', 0),
    'shipping_flat' => (float) env('SHOP_SHIPPING_FLAT', 250),
    'low_stock_threshold' => (int) env('SHOP_LOW_STOCK', 5),
    'contact_email' => env('SHOP_CONTACT_EMAIL', 'info@electromart.local'),
    'contact_phone' => env('SHOP_CONTACT_PHONE', '+92 300 1234567'),
    'contact_address' => env('SHOP_CONTACT_ADDRESS', 'Main Bazaar, Your City'),
];

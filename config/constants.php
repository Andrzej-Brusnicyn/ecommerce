<?php
return [
    'currencies' => ['USD', 'EUR', 'RUB'],
    'currency' => [
        'cache_key' => 'currency_rates',
        'cache_ttl' => 3600,
        'bank_url' => 'https://bankdabrabyt.by/export_courses.php',
    ],
    'storage' => [
        's3' => [
            'products_path' => 'products/all_products.json',
        ],
    ],
];

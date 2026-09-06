<?php

return [
    'api_key' => env('TRIPAY_API_KEY', 'DEV-MOCK-API-KEY'),
    'private_key' => env('TRIPAY_PRIVATE_KEY', 'DEV-MOCK-PRIVATE-KEY'),
    'merchant_code' => env('TRIPAY_MERCHANT_CODE', 'T0001'),
    'mode' => env('TRIPAY_MODE', 'sandbox'), // 'sandbox' or 'production'

    'api_url' => env('TRIPAY_MODE', 'sandbox') === 'production'
        ? 'https://tripay.co.id/api/'
        : 'https://tripay.co.id/api-sandbox/',

    // Saluran pembayaran populer yang didukung
    'channels' => [
        'QRIS2' => [
            'name' => 'QRIS (Semua E-Wallet & Mobile Banking)',
            'group' => 'E-Wallet',
            'fee_flat' => 750,
            'fee_percent' => 0.7,
            'icon' => 'https://tripay.co.id/images/payment-channel/qris2.png',
        ],
        'BCAVA' => [
            'name' => 'BCA Virtual Account',
            'group' => 'Virtual Account',
            'fee_flat' => 4250,
            'fee_percent' => 0,
            'icon' => 'https://tripay.co.id/images/payment-channel/bcava.png',
        ],
        'BRIVA' => [
            'name' => 'BRI Virtual Account',
            'group' => 'Virtual Account',
            'fee_flat' => 3500,
            'fee_percent' => 0,
            'icon' => 'https://tripay.co.id/images/payment-channel/briva.png',
        ],
        'MANDIRIVA' => [
            'name' => 'Mandiri Virtual Account',
            'group' => 'Virtual Account',
            'fee_flat' => 3500,
            'fee_percent' => 0,
            'icon' => 'https://tripay.co.id/images/payment-channel/mandiriva.png',
        ],
        'BNIVA' => [
            'name' => 'BNI Virtual Account',
            'group' => 'Virtual Account',
            'fee_flat' => 3500,
            'fee_percent' => 0,
            'icon' => 'https://tripay.co.id/images/payment-channel/bniva.png',
        ],
        'ALFAMART' => [
            'name' => 'Alfamart / Alfamidi',
            'group' => 'Convenience Store',
            'fee_flat' => 6000,
            'fee_percent' => 0,
            'icon' => 'https://tripay.co.id/images/payment-channel/alfamart.png',
        ],
        'INDOMARET' => [
            'name' => 'Indomaret',
            'group' => 'Convenience Store',
            'fee_flat' => 6000,
            'fee_percent' => 0,
            'icon' => 'https://tripay.co.id/images/payment-channel/indomaret.png',
        ],
    ],
];

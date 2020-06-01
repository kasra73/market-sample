<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Search services config
    |--------------------------------------------------------------------------
    |
    | This file is for storing config of services required for search
    |
    */

    'provider' => env('SEARCH_PROVIDER', 'elasticsearch'),

    'services' => [
        'elasticsearch' => [
            'class' => App\Services\Search\ElasticsearchSearchService::class
        ],
        'eloquent' => [
            'class' => App\Services\Search\EloquentSearchService::class
        ]
    ],

    'products_index' => 'market_products',

    'elasticsearch' => [
        'hosts' => [
            env('ElASTICSEARCH_HOST')
        ],
        'auth' => 'basic',
        'username' => 'elastic',
        'password' => 'trA3/E/Njd2wjB4T43Lgzw=='
    ],

];

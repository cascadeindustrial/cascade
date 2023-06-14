<?php
return [
    'backend' => [
        'frontName' => 'a82ddd9513_admin'
    ],
    'crypt' => [
        'key' => '5c2ce75dea65dd8923b4a1fcdcea2db6'
    ],
    'db' => [
        'table_prefix' => '',
        'connection' => [
            'default' => [
                'host' => 'localhost',
                'dbname' => 'morrisondirect_cascade',
                'username' => 'morrisondirect_cascade',
                'password' => 'morrisondirect_cascade',
                'model' => 'mysql4',
                'engine' => 'innodb',
                'initStatements' => 'SET NAMES utf8;',
                'active' => '1',
                'driver_options' => [
                    1014 => false
                ]
            ]
        ]
    ],
    'resource' => [
        'default_setup' => [
            'connection' => 'default'
        ]
    ],
    'x-frame-options' => 'SAMEORIGIN',
    'MAGE_MODE' => 'production',
    'session' => [
        'save' => 'files'
    ],
    'lock' => [
        'provider' => 'db',
        'config' => [
            'prefix' => ''
        ]
    ],
    'cache_types' => [
        'config' => 1,
        'layout' => 1,
        'block_html' => 1,
        'collections' => 1,
        'reflection' => 1,
        'db_ddl' => 1,
        'compiled_config' => 1,
        'eav' => 1,
        'customer_notification' => 1,
        'config_integration' => 1,
        'config_integration_api' => 1,
        'google_product' => 1,
        'full_page' => 1,
        'config_webservice' => 1,
        'translate' => 1,
        'vertex' => 1,
        'checkout' => 1,
        'amasty_shopby' => 1,
        'cart2quote_license' => 1,
        'cache_import_product' => 1,
        'mageworx_shipping_carriers' => 1
    ],
    'downloadable_domains' => [
        'www.stage.cascadeindustrial.com'
    ],
    'install' => [
        'date' => 'Wed, 24 Jun 2020 20:08:27 +0000'
    ],
    'queue' => [
        'amqp' => [
            'host' => 'localhost',
            'port' => 5672,
            'user' => 'guest',
            'password' => 'guest',
            'virtualhost' => '/'
        ]
    ],
    'http_cache_hosts' => [
        [
            'host' => 'localhost',
            'port' => '80'
        ]
    ]
];

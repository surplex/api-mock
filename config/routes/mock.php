<?php
/**
 * User: Daniel Schischkin <daniel.schischkin@surplex.com>
 * Date: 11.03.2019
 * Time: 13:03
 */
return [
    [
        'pattern'   => 'api-mock/create',
        'route'     => 'mock/mock/create',
        'verb'      => 'POST',
    ],
    [
        'pattern'   => 'api-mock/count',
        'route'     => 'mock/mock/count',
        'verb'      => 'GET',
    ],
    [
        'pattern'   => 'api-mock/clear-session',
        'route'     => 'mock/mock/clear-session',
    ],
    [
        'pattern'   => 'api-mock/client-request',
        'route'     => 'mock/mock/get-client-request',
        'verb'      => 'GET',
    ],
    [
        'pattern'  => '<url:(.*)>',
        'route'    => 'mock/mock/response',
    ]
];

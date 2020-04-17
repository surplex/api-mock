<?php
/**
 * User: Daniel Schischkin <daniel.schischkin@surplex.com>
 * Date: 11.03.2019
 * Time: 13:03
 */
return [
    [
        'pattern'   => '',
        'route'     => 'mock/mock/create',
        'verb'      => 'POST',
    ],
    [
        'pattern'   => '',
        'route'     => 'mock/mock/count',
        'verb'      => 'GET',
    ],
    [
        'pattern'   => 'clear-session',
        'route'     => 'mock/mock/clear-session',
    ],
    [
        'pattern'   => 'client-request',
        'route'     => 'mock/mock/get-client-request',
        'verb'      => 'GET',
    ],
    [
        'pattern'  => '<url:(.*)>',
        'route'    => 'mock/mock/response',
    ]
];

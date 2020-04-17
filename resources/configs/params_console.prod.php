<?php

return  [
    'version' => '1',

    //parameter checker
    'parameterChecker.file.web.master' => __DIR__ . '/params.dist.php',
    'parameterChecker.file.console.master' => __DIR__ . '/params_console.dist.php',
    'parameterChecker.files.web' => [
        'prod' => dirname(__DIR__) . '/resources/configs/params.prod.php',
        'test' => dirname(__DIR__) . '/resources/configs/params.test.php',
    ],
    'parameterChecker.files.console' => [
        'prod' => dirname(__DIR__) . '/resources/configs/params_console.prod.php',
        'test' => dirname(__DIR__) . '/resources/configs/params_console.test.php',
    ]
];

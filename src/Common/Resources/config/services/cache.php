<?php
/*
 * Surplex Components DIC Def file.
 * If no context group makes sense, place the definitions in here.
 */

return [
    /*
     * Cache
     */
    \yii\caching\Cache::class => function () {
        return Yii::$app->get('cache');
    },
];

<?php
/*
 * Surplex Components DIC Def file.
 * If no context group makes sense, place the definitions in here.
 */

return [
    /*
     * Response formats
     */
    \yii\filters\ContentNegotiator::class => [
        ['class' => \SrplxBoiler\Common\Component\ContentNegotiator::class],
        []
    ]
];

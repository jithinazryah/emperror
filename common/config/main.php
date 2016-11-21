<?php

return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'SetValues' => [
            'class' => 'common\components\SetValues'
        ],
        'UploadFile' => [
            'class' => 'common\components\UploadFile'
        ],
        'NumToWord' => [
            'class' => 'common\components\NumToWord'
        ],
    ],
];

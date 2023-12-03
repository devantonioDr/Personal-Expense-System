<?php

return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=expense_system',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8',
        ],
        'mailer' => [
            'class' => 'wadeshuler\sendgrid\Mailer',
            'viewPath' => '@common/mail',
            'useFileTransport' => false,
            'apiKey' => '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',
        ],
    ],
];


   
<?php

return [
    'adminEmail' => 'admin@example.com',
    'senderEmail' => 'noreply@example.com',
    'senderName' => 'Example.com mailer',
    'user.passwordResetTokenExpire' => 3600,
    'supportEmail' => 'robot@devreadwrite.com',
    'bsVersion' => '5.x',
    'statuses' => [
        '0' => 'Відміна',
        '1' => 'Виконано',
        '2' => 'Очікує оплати',
        '3' => 'Оплачений не відправлено',
        '4' => 'Очікує відправлення',
        '5' => 'Відправлено не оплачено',
        '6' => 'В дорозі',
        '7' => 'Митне оформленя',
        '8' => 'Нема документів',
        '9' => 'Нове замовлення',
    ],
    'document_types' => [
        'bill' => 'Рахунок',
        'invoice' => 'Накладна',
        'import' => 'Імпорт',
        'sale' => 'Реалізація',
        'income' => 'Покупка',
        'office' => 'Офіс',
    ],
    'customerTypes' => [
        'customer'  => 'Покупець',
        'supplier'  => 'Постачальник',
        'common'    => 'Загальні',
        'office'    => 'Офіс',
        'service'   => 'Послуги',
    ]
];


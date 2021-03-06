<?php

return [
    
    'site-name' => '',
    'site-name-manager' => 'ELSA | Администратор | ',
    'site-name-dispatcher' => 'ELSA | Диспетчер | ',
    
    'email_subscriber' => [
        // Имя компании, задается для email-зассылки
        'company_name' => 'ELSA',
        // Электронный адрес с котрого будет производится email-рассылка
        'company_email' => 'hi@myelsa.ru',
        // Электронный адрес поддержки пользователей
        'support_email' => 'hi@myelsa.ru',
        // Адрес портала
        'base_url' => 'http://lk.myelsa.ru',
    ],
    
    /*
     * Количество записей на страницах в таблицах
     * Модуль Сlients - Заявки, Платные услуги
     */
    'countRec' => [
        'client' => 10,
    ],
    
    /*
     * Количество символов для вывода текста комментария
     * к заявкам в таблице
     */
    'count_symbol' => 70,
    
];

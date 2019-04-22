<?php

return [
    
    'site-name' => 'ELSA | ',
    'site-name-manager' => 'ELSA | Администратор | ',
    'site-name-dispatcher' => 'ELSA | Диспетчер | ',
    
    'company-name' => 'ELSA Company',
    'company-email' => 'elsa@elsa.ru',
    
    'adminEmail' => 'admin@example.com',
    
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
    
    /*
     * День текущего месяца, когда ввод показаний будет не доступен
     */
    'finish_day' => 20,
    
    // Payments system
    'payments_system' => [
        'publicId' => 'pk_1f5d5ad761f44549ac761e0329e86',  //id из личного кабинета
        'description' => 'Оплата услуг ЖКХ',
    ],
        
];

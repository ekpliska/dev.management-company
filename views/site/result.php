<?php

/* 
 * Страница для отображения результатов проведение платежа
 * Изпользуется для редиректа в мобильных приложениях
 */

$this->title = 'Результат';
?>

<div class="result-page__content">
    
    <h1 class="result-page__status">
        <i class="glyphicon <?= $status_name === 'success' ? 'glyphicon-ok-sign' : 'glyphicon-remove-sign' ?>"></i>
    </h1>
    <h1 class="result-page__message">
        <?= $status_name === 'success' ? 'Оплачено' : 'Ошибка платежа' ?>
    </h1>
    
</div>
<?php

/* 
 * Страница для отображения результатов проведение платежа
 * Изпользуется для редиректа в мобильных приложениях
 */

$this->title = 'Результат';
$status = $status_name === 'success' ? true : false;
?>

<div class="result-page__content">
    
    <h1 class="result-page__status <?= $status ? '_result-ok' : '_result-no' ?>">
        <i class="glyphicon <?= $status ? 'glyphicon-ok-sign' : 'glyphicon-remove-sign' ?>"></i>
    </h1>
    <h1 class="result-page__message">
        <?= $status ? 'Оплачено' : 'Ошибка совершения платежа' ?>
    </h1>
    
</div>
<?php

/* 
 * Виджет для отслеживания подключения к API "Лицевой счет"
 */

?>
 <?php if ($status == false) : ?>
    <div class="status-api-info">
        <div class="status-image">
            <i class="fa fa-exclamation-triangle" aria-hidden="true"></i>
        </div>
        <div class="status-info">
            Отсутствует подключение к API, данные лицевых счетов недоступны
        </div>
    </div>
 <?php endif; ?>

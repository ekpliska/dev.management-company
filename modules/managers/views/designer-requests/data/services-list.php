<?php

/* 
 * Рендер списка услуг
 */
?>
<?php if (isset($services_list) && count($services_list) > 0) :?>
<ul class="services-list">
<?php foreach ($services_list as $key => $service) : ?>
    <li>
        <?= $service['service_name'] ?>
        <span class="span-count"><?= $service['service_id'] ?></span>
        <div class="dropdown dropdown__settings-service">
            <button type="button" class="btn-settings dropdown-toggle" data-toggle="dropdown">
                <i class="glyphicon glyphicon-option-horizontal"></i>
            </button>
            <ul class="dropdown-menu dropdown-setting">
                <li>Редактировать</li>
                <li>Удалить услугу</li>
            </ul>
        </div>
    </li>
<?php endforeach; ?>
</ul>
<?php else: ?>
<div class="notice info">
    <p>Услуги не найдены</p>
</div>
<?php endif; ?>
<?php

    use yii\helpers\Html;
    use yii\bootstrap\Modal;

/* 
 * Рендер списка услуг
 */
?>
<?php if (isset($services_list) && count($services_list) > 0) :?>
<ul class="services-list">
<?php foreach ($services_list as $service) : ?>
    <li>
        <p><?= $service['service_name'] ?></p>
        <span class="span-count"><?= "ID {$service['service_id']}" ?></span>
        <span class="span-price"><?= "&#8381; {$service['service_price']}" ?></span>
        <div class="dropdown dropdown__settings-service">
            <button type="button" class="btn-settings dropdown-toggle" data-toggle="dropdown">
                <i class="glyphicon glyphicon-option-horizontal"></i>
            </button>
            <ul class="dropdown-menu dropdown-setting">
                <li>
                    <?= Html::a('Редактировать', ['edit-service', 'service_id' => $service['service_id']], ['class' => 'edit-service-btn']) ?>
                </li>
                <li>
                    <?= Html::a('Удалить', ['/']) ?>
                </li>
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
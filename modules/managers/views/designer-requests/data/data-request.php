<?php

    use yii\helpers\Html;
    use yii\bootstrap\Modal;

/* 
 * Рендер списка вопросов для оспроса по завершенной заявки
 */
?>
<?php if (isset($results) && count($results) > 0) :?>
<ul class="services-list">
<?php foreach ($results as $question) : ?>
    <li>
        <?= $question['question_text'] ?>
        <div class="dropdown dropdown__settings-service">
            <button type="button" class="btn-settings dropdown-toggle" data-toggle="dropdown">
                <i class="glyphicon glyphicon-option-horizontal"></i>
            </button>
            <ul class="dropdown-menu dropdown-setting">
                <li>
                    <?= Html::a('Редактировать', ['edit-service', 'service_id' => $service['service_id']], ['class' => 'edit-service-btn']) ?>
                </li>
                <li>
                    <a href="javascript:void(0)" id="service__delete" data-record-type="service" data-record="<?= $service['service_id'] ?>">Удалить</a>
                </li>
            </ul>
        </div>
    </li>
<?php endforeach; ?>
</ul>
<?php else: ?>
<div class="notice info">
    <p>Вопросы не найдены</p>
</div>
<?php endif; ?>
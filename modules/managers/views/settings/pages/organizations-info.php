<?php

    use yii\widgets\ActiveForm;
    use yii\helpers\Html;
    use yii\widgets\MaskedInput;

/* 
 * Настройки реквизитов управляющей компании
 */
?>
<h4 class="title">Управляющая организация</h4>

<?php
    $form = ActiveForm::begin([
        'id' => 'edit-organizations-form',
        'validateOnChange' => false,
        'validateOnBlur' => false,
        'fieldConfig' => [
            'template' => '<div class="field"></i>{label}{input}{error}</div>',
        ],
    ]);
?>

<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <?= $form->field($model, 'organizations_name')
            ->input('text', ['class' => 'field-input'])
            ->label($model->getAttributeLabel('organizations_name'), ['class' => 'field-label']) ?>
</div>
<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
    <p class="organizations-info__title">Контактная информация</p>
    <?= $form->field($model, 'phone')
            ->input('text', ['class' => 'field-input cell-phone'])
            ->label($model->getAttributeLabel('phone'), ['class' => 'field-label']) ?>
    <?= $form->field($model, 'dispatcher_phone')
            ->input('text', ['class' => 'field-input dispatcher-phone'])
            ->label($model->getAttributeLabel('dispatcher_phone'), ['class' => 'field-label']) ?>
    <?= $form->field($model, 'email')
            ->input('text', ['class' => 'field-input'])
            ->label($model->getAttributeLabel('email'), ['class' => 'field-label']) ?>
</div>
<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
    <p class="organizations-info__title">Адрес</p>
    <?= $form->field($model, 'postcode')
            ->input('text', ['class' => 'field-input'])
            ->label($model->getAttributeLabel('postcode'), ['class' => 'field-label']) ?>
    <?= $form->field($model, 'town')
            ->input('text', ['class' => 'field-input'])
            ->label($model->getAttributeLabel('town'), ['class' => 'field-label']) ?>
    <?= $form->field($model, 'street')
            ->input('text', ['class' => 'field-input'])
            ->label($model->getAttributeLabel('street'), ['class' => 'field-label']) ?>
    <?= $form->field($model, 'house')
            ->input('text', ['class' => 'field-input'])
            ->label($model->getAttributeLabel('house'), ['class' => 'field-label']) ?>
</div>

<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <p class="organizations-info__title">
        Время работы
        <span class="message-info">Формат День-День: ЧЧ:ММ - ЧЧ:ММ; </span>
    </p>
    <?= $form->field($model, 'time_to_work')
            ->input('text', ['class' => 'field-input'])
            ->label($model->getAttributeLabel('time_to_work'), ['class' => 'field-label']) ?>
</div>

<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <p class="organizations-info__title">Реквизиты</p>
    <?= $form->field($model, 'inn')
            ->input('text', ['class' => 'field-input'])
            ->label($model->getAttributeLabel('inn'), ['class' => 'field-label']) ?>
    <?= $form->field($model, 'kpp')
            ->input('text', ['class' => 'field-input'])
            ->label($model->getAttributeLabel('kpp'), ['class' => 'field-label']) ?>
    <?= $form->field($model, 'checking_account')
            ->input('text', ['class' => 'field-input'])
            ->label($model->getAttributeLabel('checking_account'), ['class' => 'field-label']) ?>
    <?= $form->field($model, 'ks')
            ->input('text', ['class' => 'field-input'])
            ->label($model->getAttributeLabel('ks'), ['class' => 'field-label']) ?>
    <?= $form->field($model, 'bic')
            ->input('text', ['class' => 'field-input'])
            ->label($model->getAttributeLabel('bic'), ['class' => 'field-label']) ?>
</div>

<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center save-btn-group">
    <?= Html::submitButton('Сохранить', ['class' => 'btn save-settings-small']) ?>
</div>

<?php ActiveForm::end(); ?>
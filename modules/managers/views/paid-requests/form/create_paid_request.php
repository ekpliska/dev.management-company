<?php

    use yii\bootstrap\Modal;
    use yii\widgets\ActiveForm;
    use yii\helpers\Html;


/* 
 * Модальное окно "Создание заявки на платную услугу"
 */
?>

<?php
    Modal::begin([
        'id' => 'create-new-paid-requests',
        'header' => 'Новая заявка на платную услугу',
        'closeButton' => [
            'class' => 'close modal-close-btn request__btn_close',
        ],
        'clientOptions' => [
            'backdrop' => 'static',
            'keyboard' => false,
        ],        
    ]);
?>


<?php
    $form = ActiveForm::begin([
        'id' => 'create-new-request',
        'action' => ['create-paid-request'],
        'enableAjaxValidation' => true,
        'validationUrl' => ['validation-form', 'form' => 'paid-request'],
    ])
?>

    <?= $form->field($model, 'servise_category', [
                'template' => '<span class="paid-service-dropdown-arrow"><div class="field-modal-select">{label}{input}{error}</div></span>'])
            ->dropDownList($servise_category, [
                'prompt' => '[Выбрать категорию услуги]',
                'id' => 'category_service'])
            ->label(false) ?>

    <?= $form->field($model, 'servise_name', [
                'template' => '<span class="paid-service-dropdown-arrow"><div class="field-modal-select">{label}{input}{error}</div></span>'])
            ->dropDownList($servise_name, [
                'prompt' => '[-]',
                'id' => 'service_name'])
            ->label(false) ?>

    <?= $form->field($model, 'phone', ['template' => '<div class="field-modal">{label}{input}{error}</div>'])
            ->input('text', ['class' => 'field-input-modal cell-phone mobile_phone'])
            ->label($model->getAttributeLabel('phone'), ['class' => 'field-label-modal']) ?>

    <?= $form->field($model, 'flat', [
                'template' => '<span class="paid-service-dropdown-arrow"><div class="field-modal-select">{label}{input}{error}</div></span>'])
            ->dropDownList($flat, [
                'id' => 'house',
                'prompt' => '[Выбрать адрес клиента]'])
            ->label(false) ?>

    <?= $form->field($model, 'description', [
                'template' => '<div class="field-modal-textarea">{label}<span id="label-count"></span><span id="label-count-left"></span>{input}{error}</div>'])
            ->textarea(['rows' => 10, 'class' => 'field-input-textarea-modal comment'])
            ->label($model->getAttributeLabel('description'), ['class' => 'field-label-modal']) ?>

    <div class="modal-footer">
        <?= Html::submitButton('Отправить', ['class' => 'btn-modal btn-modal-yes']) ?>
        <?= Html::submitButton('Отмена', ['class' => 'btn-modal btn-modal-no request__btn_close', 'data-dismiss' => 'modal']) ?>
    </div>

<?php ActiveForm::end() ?>

<?php Modal::end(); ?>
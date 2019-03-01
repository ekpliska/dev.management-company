<?php

    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
    use yii\bootstrap\Modal;

/* 
 * Модальное окно для добавления нового вопроса, FAQ
 */
?>

<?php
    Modal::begin([
        'id' => 'add-faq-modal-form',
        'header' => 'Добавить вопрос',
        'closeButton' => [
            'class' => 'close modal-close-btn',
        ],
        'clientOptions' => [
            'backdrop' => 'static', 
            'keyboard' => false],
    ]);
?>

<?php
    $form = ActiveForm::begin([
        'id' => 'add-faq',
        'action' => ['create-record', 'model' => 'faq'],
        'enableAjaxValidation' => true,
        'validateOnChange' => false,
        'validateOnBlur' => false,
        'validationUrl' => ['validate-form', 'form' => 'add-faq'],
        'fieldConfig' => [
            'template' => '<div class="field-modal">{label}{input}{error}</div>',
        ],
    ]);
?>

    <?= $form->field($model, 'faq_question')
            ->input('text', ['class' => 'field-input-modal'])
            ->label($model->getAttributeLabel('faq_question'), ['class' => 'field-label-modal']) ?>

    <?= $form->field($model, 'faq_answer', [
                'template' => '<div class="field-modal-textarea">{label}<span id="label-count"></span><span id="label-count-left"></span>{input}{error}</div>'])
            ->textarea(['rows' => 10, 'class' => 'field-input-textarea-modal comment'])
            ->label($model->getAttributeLabel('faq_answer'), ['class' => 'field-label-modal']) ?>

    <div class="modal-footer">
        <?= Html::submitButton('Добавить', ['class' => 'btn-modal btn-modal-yes']) ?>
        <?= Html::button('Отмена', ['class' => 'btn-modal btn-modal-no', 'data-dismiss' => 'modal']) ?>
    </div>

<?php ActiveForm::end() ?>

<?php Modal::end(); ?>
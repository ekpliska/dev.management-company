<?php

    use yii\bootstrap\Modal;
    use yii\widgets\ActiveForm;
    use yii\helpers\Html;

/* 
 * Модальное окно "Добавить примечание"
 */

?>

<?php
    Modal::begin([
        'id' => 'create-new-notification',
        'header' => 'Примечание',
        'closeButton' => [
            'class' => 'close modal-close-btn',
        ],
        'clientOptions' => [
            'backdrop' => 'static',
            'keyboard' => false,
        ],        
    ]);
?>

<?php
    $form = ActiveForm::begin([
        'id' => 'create-new-notification',
        'action' => ['create-notification', 'account_id' => $account_id],
        'validateOnChange' => false,
        'validateOnBlur' => false,
        'fieldConfig' => [
            'template' => '<div class="field-modal">{label}{input}{error}</div>',
        ],
    ])
?>

    <?= $form->field($model_comment, 'title')
            ->input('text', ['class' => 'field-input-modal'])
            ->label($model_comment->getAttributeLabel('title'), ['class' => 'field-label-modal']) ?>

    <?= $form->field($model_comment, 'comments', [
                'template' => '<div class="field-modal-textarea">{label}<span id="label-count"></span><span id="label-count-left"></span>{input}{error}</div>'])
            ->textarea(['rows' => 10, 'class' => 'field-input-textarea-modal comment'])
            ->label($model_comment->getAttributeLabel('comments'), ['class' => 'field-label-modal']) ?>
            
    <div class="modal-footer">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-modal btn-modal-yes']) ?>
        <?= Html::submitButton('Отмена', ['class' => 'btn btn-modal btn-modal-no', 'data-dismiss' => 'modal']) ?>
    </div>

<?php ActiveForm::end() ?>

<?php Modal::end(); ?>
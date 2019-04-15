<?php

    use yii\bootstrap\Modal;
    use yii\widgets\ActiveForm;
    use yii\helpers\Html;
    use yii\widgets\MaskedInput;

/* 
 * Модальное окно на доабавление лицевого счета
 */
?>
<?php
    Modal::begin([
        'id' => 'create-account-modal',
        'header' => 'Добавить лицевой счет',
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
        'id' => 'create-account-modal-form',
        'action' => ['create-account'],
        'validateOnChange' => false,
        'validateOnBlur' => false,
        'enableAjaxValidation' => true,
        'validationUrl' => ['validate-form', 'form' => 'NewAccountForm'],
        'fieldConfig' => [
            'template' => '<div class="field-modal">{label}{input}{error}</div>',
            'labelOptions' => ['class' => 'label-registration hidden'],
        ],
    ]);
?>

    <?= $form->field($model, 'account_number')
//            ->input('text', ['class' => 'field-input-modal account-number'])
            ->input('text', ['class' => 'field-input-modal'])
            ->label($model->getAttributeLabel('account_number'), ['class' => 'field-label-modal']) ?>
    <?= $form->field($model, 'last_sum')
            ->widget(MaskedInput::className(), [
                    'clientOptions' => [
                        'alias' =>  'decimal',
                        'groupSeparator' => '',
                        'radixPoint' => '.',
                        'autoGroup' => false]])
            ->input('text', ['class' => 'field-input-modal'])
            ->label($model->getAttributeLabel('last_sum'), ['class' => 'field-label-modal']) ?>
    <?= $form->field($model, 'square')
            ->widget(MaskedInput::className(), [
                    'clientOptions' => [
                        'alias' =>  'decimal',
                        'groupSeparator' => '',
                        'radixPoint' => '.',
                        'autoGroup' => false]])
            ->input('text', ['class' => 'field-input-modal'])
            ->label($model->getAttributeLabel('square'), ['class' => 'field-label-modal']) ?>

    <div class="modal-footer no-border">
        <?= Html::submitButton('Создать', ['class' => 'btn-modal btn-modal-yes']) ?>
        <?= Html::button('Отмена', ['class' => 'btn-modal btn-modal-no account-create__btn_close', 'data-dismiss' => 'modal']) ?>
    </div>

<?php ActiveForm::end(); ?>

<?php Modal::end(); ?>
<?php

    use yii\bootstrap\Modal;
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;

/* 
 * Модальное окно
 * Смена пароля пользователя
 */ 
?>
<?php
Modal::begin([
    'id' => 'change_employee_password',
    'header' => 'Смена пароля пользователя, логин ' . $user->user_login,
    'closeButton' => [
        'class' => 'close modal-close-btn changes-password-form_close',
    ],
    'clientOptions' => [
        'backdrop' => 'static', 
        'keyboard' => false,
    ],    
]);
?>

    <?php 
        $form = ActiveForm::begin([
            'id' => 'changes-password-form',
            'action' => ['employee-form/change-password', 'user_id' => $user->user_id],
            'validateOnChange' => false,
            'validateOnBlur' => false,
            'fieldConfig' => [
                'template' => '<div class="field-modal">{label}{input}{error}</div>',
            ],
        ])
    ?>
        <?= $form->field($model, 'new_password')
                ->input('password', ['class' => 'field-input-modal'])
                ->label($model->getAttributeLabel('new_password'), ['class' => 'field-label-modal']) ?>

    <?= $form->field($model, 'new_password_repeat')->input('password', ['class' => 'field-input-modal'])->label($model->getAttributeLabel('new_password_repeat'), ['class' => 'field-label-modal']) ?>

    <div class="modal-footer">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn red-outline-btn bt-bottom2']) ?>
        <?= Html::button('Отмена', ['class' => 'btn btn blue-outline-btn white-btn changes-password-form_close', 'data-dismiss' => 'modal']) ?>
    </div>

<?php ActiveForm::end() ?>

<?php Modal::end() ?>
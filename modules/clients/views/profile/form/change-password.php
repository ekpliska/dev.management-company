<?php

    use yii\widgets\ActiveForm;
    use yii\helpers\Html;
    use app\modules\clients\models\form\SMSForm;
    use yii\widgets\MaskedInput;
    use app\models\SmsOperations;

/* 
 * Смена пароля учетной записи пользователя
 */

?>
<?php /* Форма смены пароля */ ?>
<?php if ($is_change_password == false) : ?>
    <?php
        $form_psw = ActiveForm::begin([
            'id' => 'change-password-form',
            'validateOnBlur' => false,
            'validateOnChange' => false,
            'fieldConfig' => [
                'template' => '{label}{input}{error}',
            ],
        ]);
    ?>

        <?= $form_psw->field($model_password, 'current_password')
                ->input('password', ['class' => 'settings-input show_password']) 
                ->label()
        ?>

        <?= $form_psw->field($model_password, 'new_password')
                ->input('password', ['class' => 'settings-input show_password'])
                ->label()
        ?>

        <?= $form_psw->field($model_password, 'new_password_repeat')
                ->input('password', ['class' => 'settings-input show_password'])
                ->label()
        ?>
        
        <div class="text-right">
            <?php if ($is_change_phone == false) : ?>
                <?= Html::submitButton('Продолжить', ['class' => 'btn-small btn-changes-yes']) ?>
            <?php endif; ?>
        </div>    

    <?php ActiveForm::end(); ?>

<?php endif; ?>

<?php /* Форма ввода СМС кода */ ?>
<?php if ($is_change_password == true) : ?>
    <?php
        $form_psw = ActiveForm::begin([
            'id' => 'sms-form',
            'action' => ['send-sms-form', 'type' => SmsOperations::TYPE_CHANGE_PASSWORD],            
            'validateOnBlur' => false,
            'validateOnChange' => false,
            'enableAjaxValidation' => true,
            'validationUrl' => ['validate-sms-form'],
            'fieldConfig' => [
                'template' => '<div class="input-block">{label}{input}{error}</div><span id="time-to-send" data-value="1"></span>',
            ],            
            'options' => [
                'class' => 'form-horizontal',
            ],
        ]);
    ?>

        <p class="message-after-sms">
            На номер вашего мобильного телефона было отправлено сообщение с кодом для подтверждения смены пароля. 
            Пожалуйта, укажите полученный код в поле ввода, которое находится ниже.
        </p>

        <?= $form_psw->field($sms_model, 'sms_code')
                ->widget(MaskedInput::className(), [
                    'mask' => '9{5,5}'])
                ->input('text', ['class' => 'settings-input input-sms_code'])
                ->label()
        ?>
    
    <div class="block-of-btn text-left">
        <?= Html::submitButton('Продолжить', ['class' => 'btn-small btn-changes-yes']) ?>
        <?= Html::button('Отмена', ['id' => 'cancel-sms', 'class' => 'btn-small btn-changes-no']) ?>
    </div>

    <?php ActiveForm::end(); ?>

<?php endif; ?>

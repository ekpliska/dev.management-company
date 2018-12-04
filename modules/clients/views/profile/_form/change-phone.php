<?php

    use yii\widgets\ActiveForm;
    use yii\widgets\MaskedInput;
    use yii\helpers\Html;
    use app\models\SmsOperations;

/* 
 * Смена номера мобильного телефона
 */
$this->title = 'Изменение номера мобильного телефона';
?>
<?php /* Форма смены пароля */ ?>
<?php if ($is_change_phone == false) : ?>
    <?php
        $form_phone = ActiveForm::begin([
            'id' => 'change-phone-form',
            'validateOnBlur' => false,
            'validateOnChange' => false,
            'fieldConfig' => [
                'template' => '{label}{input}{error}',
            ],
        ]);
    ?>

        <?= $form_phone->field($model_phone, 'new_phone')
            ->widget(MaskedInput::className(), ['mask' => '+7 (999) 999-99-99'])
                ->input('text', ['class' => 'settings-input-phone cell-phone', 'readOnly' => true, 'value' => $user_info->mobile]) 
                ->label()
        ?>
        
        <div class="text-right">
            <?= Html::submitButton('Продолжить', ['class' => 'blue-outline-btn req-table-btn change-record-btn']) ?>
        </div>    

    <?php ActiveForm::end(); ?>

<?php endif; ?>

<?php /* Форма ввода СМС кода */ ?>
<?php if ($is_change_phone == true) : ?>
    <?php
        $form_psw = ActiveForm::begin([
            'id' => 'sms-form',
            'action' => ['send-sms-form', 'type' => SmsOperations::TYPE_CHANGE_PHONE],
            'validateOnBlur' => false,
            'validateOnChange' => false,
            'enableAjaxValidation' => true,
            'validationUrl' => ['validate-sms-form'],
            'fieldConfig' => [
                'template' => '<div class="input-block">{label}{input}{error}</div><span id="time-to-send" data-value="2"></span>',
            ],            
            'options' => [
                'class' => 'form-horizontal',
            ],
        ]);
    ?>

        <?= $form_psw->field($sms_model, 'sms_code')
                ->widget(MaskedInput::className(), [
                    'mask' => '9{5,5}'])
                ->input('text', ['class' => 'settings-input input-sms_code'])
                ->label()
        ?>
    
    <div class="block-of-btn text-center">
        <?= Html::submitButton('Продолжить', ['class' => 'blue-outline-btn req-table-btn']) ?>
        <?= Html::button('Отмена', ['id' => 'cancel-sms', 'class' => 'btn red-outline-btn req-table-btn']) ?>
    </div>

    <?php ActiveForm::end(); ?>

<?php endif; ?>


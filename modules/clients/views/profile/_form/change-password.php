<?php

    use yii\widgets\ActiveForm;
    use yii\helpers\Html;
    use app\modules\clients\models\form\SMSForm;
    use yii\widgets\MaskedInput;

/* 
 * Смена пароля учетной записи пользователя
 */

?>
<?php /* Форма смены пароля */ ?>
<?php if ($is_change_password == false) : ?>
    <?php
        $form_psw = ActiveForm::begin([
            'id' => 'change-password-form',
            'validateOnSubmit' => true,
            'validateOnBlur' => false,
            'validateOnChange' => false,
        ]);
    ?>

        <?= $form_psw->field($model_password, 'current_password')
                ->input('password', [
                    'placeHolder' => $model_password->getAttributeLabel('current_password'),
                    'class' => 'form-control show_password'
                ]) 
        ?>

        <?= $form_psw->field($model_password, 'new_password')
                ->input('password', [
                    'placeHolder' => $model_password->getAttributeLabel('new_password'),
                    'class' => 'form-control show_password'])
        ?>

        <?= $form_psw->field($model_password, 'new_password_repeat')
                ->input('password', [
                    'placeHolder' => $model_password->getAttributeLabel('new_password_repeat'),
                    'class' => 'form-control show_password']) 
        ?>

        <?= Html::submitButton('Продолжить', ['class' => 'blue-outline-btn req-table-btn']) ?>

    <?php ActiveForm::end(); ?>

<?php endif; ?>

<?php /* Форма ввода СМС кода */ ?>
<?php if ($is_change_password == true) : ?>
    <?php
        $form_psw = ActiveForm::begin([
            'id' => 'sms-form',
            'validateOnSubmit' => true,
            'validateOnBlur' => false,
            'validateOnChange' => false,
            'options' => [
                'class' => 'form-horizontal',
            ],
            'fieldConfig' => [
                'template' => '<div class="form-row">'
                . '<div class="col-sm-2">{label}{input}{error}</div>'
                . '<div class="col-sm-8"><div class="block-of-repeat"><span id="time-to-send"></span></div>'
                . '</div>'
                . '</div>',
            ],
        ]);
    ?>

        <?= $form_psw->field($sms_model, 'sms_code')
                ->widget(MaskedInput::className(), [
                    'mask' => '9{5,5}'])
                ->input('text', [
                    'class' => 'form-control input-sms_code',
                ]) 
        ?>

        <?= Html::submitButton('Продолжить', ['class' => 'blue-outline-btn req-table-btn']) ?>

    <?php ActiveForm::end(); ?>

<?php endif; ?>

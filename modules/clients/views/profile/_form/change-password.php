<?php

    use yii\widgets\ActiveForm;
    use yii\helpers\Html;
    use app\modules\clients\models\form\SMSForm;

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

        <?= Html::submitButton('Продолжить', ['class' => 'btn btn-primary']) ?>

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
        ]);
    ?>

        <?= $form_psw->field($sms_model, 'sms_code')
                ->input('text', [
                    'placeHolder' => $sms_model->getAttributeLabel('sms_code'),
                    'class' => 'form-control show_password'
                ]) 
        ?>

        <?= Html::submitButton('Продолжить', ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Выслать код повторно', ['profile/repeat-send-sms']) ?>

    <?php ActiveForm::end(); ?>

<?php endif; ?>

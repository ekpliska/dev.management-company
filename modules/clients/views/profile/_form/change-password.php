<?php

    use yii\widgets\ActiveForm;
    use yii\helpers\Html;

/* 
 * Смена пароля учетной записи пользователя
 */

?>
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

<?= Html::submitButton('Изменить', ['class' => 'btn btn-primary']) ?>
                
<?php ActiveForm::end(); ?>
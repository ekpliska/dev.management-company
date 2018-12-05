<?php

    use yii\widgets\ActiveForm;
    use yii\helpers\Html;

/* 
 * Смена электронного адреса
 */
?>
<p class="settings-message">
    Внимание, на указанный адрес электронной почты будут приходить оповещения с портала. 
    Отключить оповещения вы можете на странице вашего профиля <?= Html::a('Профиль', ['profile/index']) ?>.
</p>
<?php
    $form_email = ActiveForm::begin([
        'id' => 'change-email-form',
        'validateOnSubmit' => true,
        'validateOnBlur' => false,
        'validateOnChange' => false,
        'options' => [
            'class' => 'form-inline',
        ],
    ]);
?>
    <?= $form_email->field($user, 'user_email')
            ->input('text', ['class' => 'settings-input-phone email-inp', 'readOnly' => true])
            ->label(false) ?>

    <?= Html::submitButton('<i class="glyphicon glyphicon-pencil"></i>', ['class' => 'change-btn-edit change-record-btn']) ?>

<?php ActiveForm::end() ?>
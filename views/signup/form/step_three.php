<?php

    use yii\widgets\ActiveForm;
    use yii\helpers\Html;
    use app\widgets\UserAgreement;
    
/* 
 * Регистрация, шаг 3
 */
?>
<?php
    $form = ActiveForm::begin([
        'id' => 'signup-form-step-two',
        'validateOnChange' => false,
        'validateOnBlur' => false,
        'fieldConfig' => [
            'template' => '{label}{input}',
            'labelOptions' => ['class' => 'label-registration hidden'],
        ],
        'options' => [
            'class' => 'form-signin',
        ],
    ])
?>

    <?= $form->errorSummary($model_step_three, ['header' => '']); ?>

    <div class="field">
        <?= $form->field($model_step_three, 'phone')
                ->input('text', ['class' => 'field-input cell-phone'])
                ->label($model_step_three->getAttributeLabel('phone'), ['class' => 'field-label']) ?> 
        <?= Html::button('Получить код', ['id' => 'send-request-to-sms']) ?>
        <p id="error-message"></p>
    </div>

    <div class="field-sms">
        <?= $form->field($model_step_three, 'sms_code', [
                'template' => '{label}{input}<span id="timer-to-send"></span>'])
                ->input('text', ['class' => 'field-input sms-code-input'])
                ->label($model_step_three->getAttributeLabel('sms_code'), ['class' => 'field-label']) ?>
    </div>

    <div class="text-center third-step-offer">
        <p>
            Нажимая на кнопку Далее, вы соглашаетесь на обработку 
            Персональных данных и принимаете условия 
            <a href="#user_agreement" data-toggle="modal">Пользовательского соглашения.</a>
        </p>

        <?= Html::submitButton('', ['class' => 'blue-circle-btn']) ?>    
    </div>


<?php ActiveForm::end(); ?>

<?= UserAgreement::widget() ?>
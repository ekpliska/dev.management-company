<?php
    use yii\widgets\ActiveForm;
    use yii\helpers\Html;
    use yii\helpers\Url;
    
$this->title = "Customers | Восстановаление пароля";
?>

<a style="display:block" href="<?= Url::to(['/']) ?>">
    <div class="registration-logo"></div>
</a>
<div class="slide-content">
    <h2 class="text-center registration-title">
        Восстановление пароля        
    </h2>
    <div class="login-form-group">
        <?php 
            var_dump(Yii::$app->session['reset_sms_code']);
            var_dump($expired_at = Yii::$app->session->has('reset_expired_at'));
        ?>
        
        <?php
            $form = ActiveForm::begin([
                'id' => 'password-reset-form',
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
        
            <?= $form->errorSummary($model, ['header' => '']); ?>
        
            <small>Для восстановление пароля введите номер вашего мобильного телефона, указанный при регистрации</small>
        
            <div class="field">
                <?= $form->field($model, 'phone')
                        ->input('text', ['class' => 'field-input cell-phone'])
                        ->label($model->getAttributeLabel('phone'), ['class' => 'field-label']) ?> 
                <?= Html::button('Получить код', ['id' => 'reset-password-sms']) ?>
                <p id="error-message"></p>
            </div>

            <div class="field-sms">
                <?= $form->field($model, 'sms_code', [
                            'template' => '{label}{input}<span id="timer-to-send"></span>'])
                        ->input('text', ['class' => 'field-input sms-code-input'])
                        ->label($model->getAttributeLabel('sms_code'), ['class' => 'field-label']) ?>
            </div>

            <div class="login-form__button text-center">
                <?= Html::submitButton('Отправить', ['class' => 'btn blue-btn', 'name' => 'login-button']) ?>
                <?= Html::a('Отмена', ['/'], ['class' => 'btn btn-link red-btn-link']) ?>
            </div>


        <?php ActiveForm::end(); ?>
    
    </div>
</div>
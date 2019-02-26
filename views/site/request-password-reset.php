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

        <?php if (Yii::$app->session->hasFlash('success')): ?>
            <div class="alert alert-register-success alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <?php echo Yii::$app->session->getFlash('success'); ?>
            </div>
        <?php endif; ?>
        <?php if (Yii::$app->session->hasFlash('error')): ?>
            <div class="alert alert-register-error alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <?php echo Yii::$app->session->getFlash('error'); ?>
            </div>
        <?php endif; ?>

        
            <?= $form->errorSummary($model, ['header' => '']); ?>
        
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
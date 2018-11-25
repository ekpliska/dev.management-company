<?php

    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\widgets\ActiveForm;

$this->title = 'Вход';
?>

<a style="display:block" href="<?= Url::to(['/']) ?>">
    <div class="registration-logo"></div>
</a>
<div class="slide-content">
    <h2 class="text-center registration-title">
        Вход
    </h2>
    <div class="login-form-group">
        <?php
            $form = ActiveForm::begin([
                'id' => 'login-form',
                'validateOnChange' => false,
                'validateOnBlur' => false,
                'fieldConfig' => [
                    'template' => '<div class="field">{label}{input}{error}</div>',
                    'labelOptions' => ['class' => 'label-registration hidden'],
                ],
            ])
        ?>
            <?= $form->field($model, 'username')
                    ->input('text', [
                        'class' => 'input-registration field-input'])
                    ->label($model->getAttributeLabel('username'), ['class' => 'field-label']) ?>

            <?= $form->field($model, 'password')
                    ->input('password', [
                        'class' => 'input-registration field-input'])
                    ->label($model->getAttributeLabel('password'), ['class' => 'field-label']) ?>

            <div class="login-form__button text-center">
                <?= Html::submitButton('Вход', [
                        'class' => 'btn blue-btn', 
                        'name' => 'login-button']) ?>
                <?= Html::a('Отмена', ['/'], ['class' => 'btn red-btn']) ?>
            </div>

            <div class="text-center">
                <a href="<?= Url::to(['site/request-password-reset']) ?>" class="forgot-a">Забыли пароль?</a>        
            </div>


        <?php ActiveForm::end(); ?>    
    </div>
</div>
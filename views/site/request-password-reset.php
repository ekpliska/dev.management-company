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
                    'template' => '<div class="field">{label}{input}</div>',
                    'labelOptions' => ['class' => 'label-registration hidden'],
                ],
                'options' => [
                    'class' => 'form-signin',
                ],
            ])
        ?>
            <?= $form->field($model, 'email')
                    ->input('text', ['class' => 'field-input'])
                    ->label($model->getAttributeLabel('email'), ['class' => 'field-label']) ?>

            <small>Для восстановление пароля введите адрес электронной почты, указанный при регистрации</small>

            <div class="login-form__button text-center">
                <?= Html::submitButton('Отправить', ['class' => 'btn blue-btn', 'name' => 'login-button']) ?>
                <?= Html::a('Отмена', ['/'], ['class' => 'btn red-btn']) ?>
            </div>


        <?php ActiveForm::end(); ?>
    
    </div>
</div>
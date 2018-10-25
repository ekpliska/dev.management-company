<?php
    use yii\bootstrap\ActiveForm;
    use yii\helpers\Html;
    use yii\helpers\Url;
    
$this->title = "Customers | Восстановаление пароля";
?>

<h1 class="text-center registration-logo">
    <?= Html::img('images/main/elsa-logo13@2x.png', ['class' => 'blue-logo']) ?>
</h1>
<div class="slide-content tst2">
    <h2 class="text-center registration-h blue-txt">
        Восстановление пароля
    </h2>
    
    <?php
        $form = ActiveForm::begin([
            'id' => 'password-reset-form',
            'fieldConfig' => [
                'template' => "{label}{input}{error}",
                'labelOptions' => ['class' => 'label-registration hidden'],
            ],
            'options' => [
                'class' => 'form-signin d-block my-auto material',
            ],
        ])
    ?>
    
    <div class="mx-auto registration-form-group">

        <?= $form->field($model, 'email')
                ->input('text', [
                    'class' => 'mx-auto py-3 d-block form-control input-registration', 
                    'placeholder' => $model->getAttributeLabel('email')])
                ->label(true) ?>

        <small>Для восстановление пароля введите адрес электронной почты, указанный при регистрации</small>
                
    </div>
    <div class="registration-btn-group mx-auto">
        <div class="text-center">
            
            <?= Html::submitButton('Отправить', ['class' => 'btn blue-btn', 'name' => 'login-button']) ?>
            <?= Html::a('Отмена', ['site/login'], ['class' => 'btn red-outline-btn']) ?>
            
        </div>
    </div>
    
    <?php ActiveForm::end(); ?>
    
</div>

<?php
$this->registerJs("
    $('form.material').materialForm();
");
?>
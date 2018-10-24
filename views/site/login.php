<?php

    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\bootstrap\ActiveForm;

$this->title = 'Вход';
?>


<h1 class="text-center registration-logo">
    <?= Html::img('images/main/elsa-logo13@2x.png', ['class' => 'blue-logo']) ?>
</h1>
<div class="slide-content tst2">
    <h2 class="text-center registration-h blue-txt">
        Вход
    </h2>
    
    <?php
        $form = ActiveForm::begin([
            'id' => 'login-form',
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
        <?= $form->field($model, 'username')
                ->input('text', [
                    'class' => 'mx-auto py-3 d-block form-control input-registration', 
                    'placeholder' => $model->getAttributeLabel('username')])
                ->label(true) ?>
        
        <?= $form->field($model, 'password')
                ->input('password', [
                    'class' => 'mx-auto py-3 d-block form-control input-registration',                     
                    'placeholder' => $model->getAttributeLabel('password')])
                ->label(true) ?>
        
        <?php /*= $form->field($model, 'rememberMe')->checkbox([
                'template' => "<div class=\"col-sm-12\">{input} {label}</div>\n<div class=\"col-sm-12\">{error}</div>",
            ]) */ ?>
    </div>
    <div class="registration-btn-group mx-auto">
        <div class="text-center">
            
            <?= Html::submitButton('Вход', [
                'class' => 'btn blue-btn', 
                'name' => 'login-button']) ?>
    
            <?= Html::a('Отмена', ['/'], ['class' => 'btn red-outline-btn']) ?>
            
        </div>
    </div>
    
    <?php ActiveForm::end(); ?>
    
</div>
<div class=" fixed-bottom col-6 ml-auto">
    <p class=" text-muted text-center mb-2">
        <a href="<?= Url::to(['site/request-password-reset']) ?>" class="forgot-a gray-txt no-underline">Забыли пароль?</a>
    </p>
</div>

<?php
$this->registerJs("
    $('form.material').materialForm();
");
?>
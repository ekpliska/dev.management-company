<?php
    use yii\bootstrap\ActiveForm;
    use yii\helpers\Html;
    use yii\widgets\MaskedInput;
    use yii\helpers\Url;

/* 
 * Форма регистрации
 */
$this->title = 'Customers | Регистрация';
?>
    <style type="text/css">
        legend { font-size:18px; margin:0px; padding:10px 0px; color:#b0232a; font-weight:bold;}
        #steps { list-style:none; width:100%; overflow:hidden; margin:0px; padding:0px;}
        #steps li {font-size:24px; float:left; padding:10px; color:#b0b1b3;}
        #steps li span {font-size:11px; display:block;}
        #steps li.current { color:#000;}
    </style>


<h1 class="text-center registration-logo">
    <?= Html::img('/images/main/elsa-logo13@2x.png', ['class' => 'blue-logo']) ?>
</h1>
<div class="slide-content tst2">
    <h2 class="text-center registration-h blue-txt">
        Регистрация
    </h2>


        <ul id="steps">
            <li id="stepDesc0" class="current">Шаг 1<span>Лицевой счет</span></li>
            <li id="stepDesc1">Шаг 2<span>Пользовательские данные</span></li>
            <li id="stepDesc2">Шаг 3<span>Завершение регистрации</span></li>
        </ul>
     
        <div id="step0">
            <fieldset>
                <!--<legend>Лицевой счет</legend>-->
                <?= $this->render('form/step_one', ['model_step_one' => $model_step_one]) ?>
                
            </fieldset>
        </div>
            
        <div id="step1" style="display: none;">
            <fieldset>
                <legend>Пользовательские данные</legend>
                <?= $this->render('form/step_one', ['model_step_one' => $model_step_one]) ?>
                
                
            </fieldset>
        </div>
            
        <div id="step2" style="display: none;">
            <fieldset>
                <legend>Завершение регистрации</legend>
                <label for="NameOnCard">Name on Card</label>
                <input id="NameOnCard" type="text">
                <label for="CardNumber">Card Number</label>
                <input id="CardNumber" type="text">
                <label for="CreditcardMonth">Expiration</label>
                <p id="step2commands"><a href="#" id="step2Prev" class="prev">&lt; Back</a></p>
            </fieldset>
        </div>
    

    
    <?php /*
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
        
        <?= $form->field($model, 'last_sum')
                ->input('input', [
                    'class' => 'mx-auto py-3 d-block form-control input-registration',
                    'placeholder' => $model->getAttributeLabel('last_sum')])
                ->label(true) ?>
        
        <?= $form->field($model, 'square')
                ->input('text', [
                    'class' => 'mx-auto py-3 d-block form-control input-registration',
                    'placeholder' => $model->getAttributeLabel('square')])
                ->label(true) ?>
        
        <?= $form->field($model, 'mobile_phone')
                ->widget(MaskedInput::className(), [
                    'mask' => '+7 (999) 999-99-99',
                ])
                ->input('text', [
                    'placeholder' => $model->getAttributeLabel('mobile_phone'),
                    'class' => 'mx-auto py-3 d-block form-control input-registration',
                    ])
                ->label(true) ?>
        
        
        <?= $form->field($model, 'email')
                ->input('text', [
                    'placeholder' => $model->getAttributeLabel('email'),
                    'class' => 'mx-auto py-3 d-block form-control input-registration',
                    ])
                ->label(true) ?>
        
        <?= $form->field($model, 'password_repeat')
                ->input('password', [
                    'placeholder' => $model->getAttributeLabel('password_repeat'),
                    'class' => 'mx-auto py-3 d-block form-control input-registration',
                    ])
                ->label(true) ?>

        <?= $form->field($model, 'password')
                ->input('password', [
                    'placeholder' => $model->getAttributeLabel('password'),
                    'class' => 'mx-auto py-3 d-block form-control input-registration',
                    ])
                ->label(true) ?> 
        
    </div>
    <div class="registration-btn-group mx-auto">
        <div class="text-center">
            
            <?= Html::submitButton('Вход', [
                'class' => 'btn blue-btn', 
                'name' => 'login-button']) ?>
    
            <?= Html::a('Отмена', ['/'], ['class' => 'btn red-outline-btn']) ?>
            
        </div>
    </div>
    
    <?php ActiveForm::end(); */ ?>
    
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
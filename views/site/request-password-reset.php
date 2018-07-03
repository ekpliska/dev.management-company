<?php
    use yii\bootstrap\ActiveForm;
    use yii\helpers\Html;
    use yii\helpers\Url;
    
$this->title = "Customers | Восстановаление пароля";
?>
<div class="site-login">
    <h1>Восстановление пароля</h1>
    
        <div class="col-md-12 text-left">
            <?php 
                $form = ActiveForm::begin([
                    'id' => 'password-reset-form',
                    'fieldConfig' => [
                        'template' => "{label}\n<div class=\"col-sm-7\">{input}</div>\n<div class=\"col-sm-7\">{error}</div>",
                            'labelOptions' => ['class' => 'col-sm-5 control-label'],
                        ],
                    'options' => [
                        'class' => 'form-horizontal',
                    ]
                    ])
            ?>
                <div class="col-md-4">
                    <p>Для восстановление пароля введите адрес электронной почты, указанной при регистрации</p>
                </div>
                <div class="col-md-8">
                    <?= $form->field($model, 'email')->input('text')->label(false) ?>
                </div>

                <div class="form-group">
                    <div class="col-sm-12">
                        <?= Html::submitButton('Отправить', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-12">
                        <a href="<?= Url::to(['/']) ?>">Вход</a> | 
                        <a href="<?= Url::to(['site/registration']) ?>">Регистрация</a>
                    </div>
                </div>                
                
            <?php ActiveForm::end(); ?>
            
        </div>    

</div>
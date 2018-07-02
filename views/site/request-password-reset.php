<?php
    use yii\bootstrap\ActiveForm;
    use yii\helpers\Html;
    
$this->title = "Customers | Восстановаление пароля";
?>
<div class="site-login">
    <h1>Восстановление пароля</h1>
    
        <div class="col-md-5">
            <?php 
                $form = ActiveForm::begin([
                    'id' => 'password-reset-form',
                    'fieldConfig' => [
                        'template' => "{label}\n<div class=\"col-sm-10\">{input}</div>\n<div class=\"col-sm-10\">{error}</div>",
                            'labelOptions' => ['class' => 'col-sm-2 control-label'],
                        ],
                    'options' => [
                        'class' => 'form-horizontal',
                    ]
                    ])
            ?>
                <p>Для восстановление пароля введите адрес электронной почты, указанной при регистрации: </p>
                <?= $form->field($model, 'email')->input('text')->label(true) ?>                    

                <div class="form-group">
                    <div class="col-sm-12">
                        <?= Html::submitButton('Отправить', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                    </div>
                </div>

            <?php ActiveForm::end(); ?>
            
        </div>    

</div>
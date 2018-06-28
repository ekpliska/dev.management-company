<?php
    
    use yii\bootstrap\ActiveForm;
    use yii\helpers\Html;
    use yii\helpers\Url;

$this->title = 'Customers | Вход';
?>
<div class="site-index">

    <div class="body-content">

        <div class="row">
            <div class="col-lg-5">
                <h2>Вход</h2>
                <?php 
                    $form = ActiveForm::begin([
                        'id' => 'login-form',
                        'fieldConfig' => [
                            'template' => "{label}\n<div class=\"col-sm-10\">{input}</div>\n<div class=\"col-sm-10\">{error}</div>",
                            'labelOptions' => ['class' => 'col-sm-2 control-label'],
                        ],
                        'options' => [
                            'class' => 'form-horizontal',
                        ]
                    ])
                ?>
                
                    <?= $form->field($model, 'username')->input('text', ['placeholder' => $model->getAttributeLabel('username')])->label(true) ?>                    
                    
                    <?= $form->field($model, 'password')->input('text', ['placeholder' => $model->getAttributeLabel('password')])->label(true) ?>
                
                    <?= $form->field($model, 'rememberMe')->checkbox([
                        'template' => "<div class=\"col-sm-12\">{input} {label}</div>\n<div class=\"col-sm-12\">{error}</div>",
                    ]) ?>
                
                    <div class="form-group">
                        <div class="col-sm-12">
                            <?= Html::submitButton('Login', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <div class="col-sm-12">
                            <a href="<?= Url::to(['/']) ?>">Забыли пароль?</a> | 
                            <a href="<?= Url::to(['site/registration']) ?>">Регистрация</a>
                        </div>
                    </div>
                
                <?php ActiveForm::end(); ?>
            </div>
            <div class="col-lg-7">
                <h2>title</h2>

                <p>
                    text
                </p>

                <p><a class="btn btn-default" href="http://www.yiiframework.com/forum/">Yii Forum &raquo;</a></p>
            </div>
        </div>

    </div>
</div>

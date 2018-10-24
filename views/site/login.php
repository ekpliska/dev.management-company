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
    
            <?= Html::button('Отмена', ['class' => 'btn red-outline-btn']) ?>
            
        </div>
    </div>
    
    <?php ActiveForm::end(); ?>
    
</div>
<div class=" fixed-bottom col-6 ml-auto">
    <p class=" text-muted text-center mb-2">
        <a href="<?= Url::to(['site/request-password-reset']) ?>" class="forgot-a gray-txt no-underline">Забыли пароль?</a>
        <a href="<?= Url::to(['site/registration']) ?>">Регистрация</a>
    </p>
</div>

<?php
$this->registerJs("
    $('form.material').materialForm();
");
?>

<?php /*
<div class="site-login">
    <h1>Вход</h1>
        <?php if (Yii::$app->session->hasFlash('registration-done')) : ?>
            <div class="alert alert-info" role="alert">
                <strong>
                    <?= Yii::$app->session->getFlash('registration-done', false); ?>
                </strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>                    
            </div>
        <?php endif; ?>
    
        <?php if (Yii::$app->session->hasFlash('registration-error')) : ?>
            <div class="alert alert-error" role="alert">
                <strong>
                    <?= Yii::$app->session->getFlash('registration-error', false); ?>
                </strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>                
            </div>
        <?php endif; ?>

        <div class="col-md-5">
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

                <?= $form->field($model, 'password')->input('password', ['placeholder' => $model->getAttributeLabel('password')])->label(true) ?>

                <?= $form->field($model, 'rememberMe')->checkbox([
                    'template' => "<div class=\"col-sm-12\">{input} {label}</div>\n<div class=\"col-sm-12\">{error}</div>",
                ]) ?>

                <div class="form-group">
                    <div class="col-sm-12">
                        <?= Html::submitButton('Вход', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-12">
                        <a href="<?= Url::to(['site/request-password-reset']) ?>">Забыли пароль?</a> | 
                        <a href="<?= Url::to(['site/registration']) ?>">Регистрация</a>
                    </div>
                </div>

            <?php ActiveForm::end(); ?>
            
        </div>    

<!--    <div class="col-lg-offset-1" style="color:#999;">
        You may login with <strong>admin/admin</strong> or <strong>demo/demo</strong>.<br>
        To modify the username/password, please check out the code <code>app\models\User::$users</code>.
    </div>-->
</div>
 */ ?>
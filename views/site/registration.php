<?php
    use yii\bootstrap\ActiveForm;
    use yii\helpers\Html;
    use yii\widgets\MaskedInput;

/* 
 * Форма регистрации
 */
$this->title = 'Customers | Регистрация';
$this->params['breadcrumbs'][] = 'Регистрация';
?>
<div class="site-registration">
    <h1>Registration</h1>
    <div class="row">
        <div class="col-lg-7">
            <?php
                $form = ActiveForm::begin([
                    'id' => 'registration-form',
                    'enableClientValidation' => true,
                    'enableAjaxValidation' => false,
                    'fieldConfig' => [
                        'template' => "{label}\n<div class=\"col-sm-7\">{input}</div>\n<div class=\"col-sm-12\">{error}</div>",
                        'labelOptions' => ['class' => 'col-sm-5 control-label'],
                    ],
                    'options' => [
                        'class' => 'form-horizontal',
                    ]                    
                ])
            ?>

                <?= $form->field($model, 'username')->textInput(['placeholder' => $model->getAttributeLabel('username')])->label(true) ?>
            
                <?= $form->field($model, 'last_sum')->input('text', ['placeholder' => $model->getAttributeLabel('last_sum')])->label(true) ?>

                <?= $form->field($model, 'square')->input('text', ['placeholder' => $model->getAttributeLabel('square')])->label(true) ?>
            
                <?= $form->field($model, 'mobile_phone')->input('text', ['placeholder' => $model->getAttributeLabel('mobile_phone')])->label(true) ?>

                <?= $form->field($model, 'email')->input('text', ['placeholder' => $model->getAttributeLabel('email')])->label(true) ?>

                <?= $form->field($model, 'password_repeat')->input('password', ['placeholder' => $model->getAttributeLabel('password_repeat')])->label(true) ?>

                <?= $form->field($model, 'password')->input('password', ['placeholder' => $model->getAttributeLabel('password')])->label(true) ?>                        

                <div class="form-group">
                    <div class="col-sm-12 text-right">
                        <?= Html::submitButton('Зарегистрироваться', ['class' => 'btn btn-primary']) ?>
                    </div>
                </div>
            
            <?php ActiveForm::end() ?>
        </div>
    </div>
</div>
    
    
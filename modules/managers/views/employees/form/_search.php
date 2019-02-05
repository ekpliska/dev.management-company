<?php

    use yii\helpers\Html;
    use yii\widgets\ActiveForm;

/* 
 * Форма поиска
 */
$action = Yii::$app->controller->action->id;
?>

<div class="container-fluid submenu-manager text-center">
    <ul class="nav navbar-nav navbar-left">
        <li>
            <?php 
                $form = ActiveForm::begin([
                    'id' => 'search-dispatchers-form',
                    'action' => ["$action"],
                    'method' => 'get',
                    'fieldConfig' => [
                        'template' => '{input}',
                    ],
                    'options' => [
                        'class' => 'form-inline',
                    ],
                ]);
            ?>
            
            <?= $form->field($model, 'name')->input('text', ['class' => '_search-input', 'placeHolder' => 'Фамилия имя отчество'])->label(false) ?>
            
            <div class="form-group">
                
            <?= $form->field($model, 'employee_department_id')->dropDownList($departments, [
                    'prompt' => '[Подразделение]',
                    'class' => '_dropdown-subpanel _small']) ?>
                
            <?= $form->field($model, 'employee_posts_id')->dropDownList($posts, [
                    'prompt' => '[Должность]',
                    'class' => '_dropdown-subpanel _small']) ?>
                
            </div>
            <?= Html::submitButton('', ['class' => 'btn search-block__button']) ?>
            
            <?php ActiveForm::end(); ?>
        </li>
    </ul>
</div>
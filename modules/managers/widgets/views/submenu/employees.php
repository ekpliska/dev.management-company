<?php

    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\widgets\ActiveForm;

    
/*
 * Вид навигационного меню, блок Собственники
 */    
?>

<?php if (Yii::$app->controller->id == '_employees' && Yii::$app->controller->action->id == 'dispatchers') : ?>
<div class="container-fluid submenu-manager text-center">
    <ul class="nav navbar-nav navbar-left">
        <li>
            <?php 
                $form = ActiveForm::begin([
                    'id' => 'search-dispatchers-form',
                    'action' => ['dispatchers'],
                    'method' => 'get',
                    'fieldConfig' => [
                        'template' => '{input}',
                    ],
                    'options' => [
                        'class' => 'form-inline',
                    ],
                ]);
            ?>
            
            <?= $form->field($model, 'name')->input('text', ['class' => '_search-input', 'placeHolder' => 'Фамилия имя отчество'])->label() ?>
            
            <div class="form-group">
                
            <?= $form->field($model, 'employee_department_id')->dropDownList($params['departments'], [
                    'prompt' => '[Подразделение]',
                    'class' => '_dropdown-subpanel']) ?>
                
            <?= $form->field($model, 'employee_posts_id')->dropDownList($params['posts'], [
                    'prompt' => '[Должность]',
                    'class' => '_dropdown-subpanel']) ?>
                
            </div>
            <?= Html::submitButton('', ['class' => 'btn search-block__button']) ?>
            
            <?php ActiveForm::end(); ?>
        </li>
    </ul>
</div>
<?php endif; ?>
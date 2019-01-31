<?php

    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\widgets\ActiveForm;

    
/*
 * Вид навигационного меню, блок Собственники
 */    
?>

<?php if (Yii::$app->controller->id == 'clients' && Yii::$app->controller->action->id == 'index') : ?>
<div class="container-fluid submenu-manager text-center">
    <ul class="nav navbar-nav navbar-left">
        <li>
            <?php 
                $form = ActiveForm::begin([
                    'id' => 'search-clients-form',
                    'fieldConfig' => [
                        'template' => '{input}',
                    ],
                    'options' => [
                        'class' => 'form-inline',
                    ],
                ]);
            ?>
            
            <?= $form->field($model, 'input_value')
                    ->input('text', ['class' => 'search-block__input-dark'])
                    ->label(false) ?>
            
            <?= Html::submitButton('Поиск', ['class' => 'search-block__button']) ?>
            
            <?php ActiveForm::end(); ?>
        </li>
    </ul>
</div>
<?php endif; ?>
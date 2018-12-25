<?php

    use yii\helpers\Html;
    use yii\helpers\Url;

    
/*
 * Вид навигационного меню, блок Собственники
 */    
?>
<?php if (
        (Yii::$app->controller->id == 'clients' || Yii::$app->controller->id == 'managers') 
        && Yii::$app->controller->action->id == 'index') : ?>
    <div class="container-fluid submenu-manager text-center">
        <ul class="nav navbar-nav navbar-left">
            <li>
                <?= Html::input('text', '_search-input', null, ['class' => '_search-input', 'placeHolder' => 'Поиск']) ?>
            </li>
        </ul>
     </div>
<?php endif; ?>
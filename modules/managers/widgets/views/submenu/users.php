<?php

    use yii\helpers\Html;
    use yii\helpers\Url;

    
/*
 * Вид навигационного меню, блок Собственники
 */    
$arrya_controllers = [
    'clients', 
    'managers', 
    'employees',
];
$arrya_actions = [
    'index', 
    'dispatchers', 
    'specialists',
];
?>

<?php if (in_array(Yii::$app->controller->id, $arrya_controllers) && in_array(Yii::$app->controller->action->id, $arrya_actions)) : ?>

<div class="container-fluid submenu-manager text-center">
    <ul class="nav navbar-nav navbar-left">
        <li>
            <?= Html::input('text', '_search-input', null, ['class' => '_search-input', 'placeHolder' => 'Поиск']) ?>
        </li>
    </ul>
</div>
<?php endif; ?>
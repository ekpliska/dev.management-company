<?php

    use yii\helpers\Html;

    
/*
 * Дополнительное навигационное меню, Жилищный фонд
 */    
$arrya_controllers = [
    'housing-stock',
];
$arrya_actions = [
    'index', 
];
?>

<?php if (in_array(Yii::$app->controller->id, $arrya_controllers) && in_array(Yii::$app->controller->action->id, $arrya_actions)) : ?>

<div class="container-fluid submenu-manager text-center">
    <ul class="nav navbar-nav navbar-left">
        <li>
            #TODO
        </li>
    </ul>
</div>
<?php endif; ?>
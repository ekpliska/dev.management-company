<?php

    use yii\helpers\Html;

    
/*
 * Дополнительное навигационное меню, Заявки и Платные услуги
 */    
$arrya_controllers = [
    'requests',
];
$arrya_actions = [
    'requests', 
    'paid-services', 
];
?>

<?php if (in_array(Yii::$app->controller->id, $arrya_controllers) && in_array(Yii::$app->controller->action->id, $arrya_actions)) : ?>

<div class="container-fluid submenu-manager text-center">
    <ul class="nav navbar-nav navbar-left">
        <li>
            #TODO
        </li>
        <li>
            #TODO
        </li>
    </ul>
</div>
<?php endif; ?>
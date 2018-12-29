<?php

    use yii\helpers\Html;

    
/*
 * Дополнительное навигационное меню, Голосование
 */    
$arrya_controllers = [
    'voting',
];
$arrya_actions = [
    'index', 
];
?>

<?php if (in_array(Yii::$app->controller->id, $arrya_controllers) && in_array(Yii::$app->controller->action->id, $arrya_actions)) : ?>

<div class="container-fluid submenu-manager text-center">
    <ul class="nav navbar-nav navbar-left">
        <li>
            <?= Html::input('text', '_search-input', null, ['class' => '_search-input', 'placeHolder' => 'Поиск']) ?>
        </li>
        <li>
            <?= Html::dropDownList('category_list', null, $params, [
                    'placeholder' => 'Выбрать адрес из списка...',
                    'id' => 'sources-adress',
                    'class' => 'custom-select-services select-adress-house',
                    'data-type-page' => 'voting']) 
            ?>
        </li>
    </ul>
</div>
<?php endif; ?>
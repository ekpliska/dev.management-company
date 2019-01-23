<?php

    use yii\helpers\Url;

/* 
 * Дополнительное навигационное меню для разделла "Коструктор заявок"
 */
$arrya_controllers = [
    'designer-requests',
];
$arrya_actions = [
    'index', 
];
?>
<?php if (in_array(Yii::$app->controller->id, $arrya_controllers) && in_array(Yii::$app->controller->action->id, $arrya_actions)) : ?>

<?php $current_section = Yii::$app->controller->actionParams['section']; ?>

    <div class="container-fluid navbar-general-page text-center">
        <ul class="nav nav-pills navbar__pills">
            <?php foreach ($params as $key => $item) : ?>
                <li class="nav-item <?= ($current_section == $key) ? 'active' : '' ?>">
                    <a href="<?= Url::to(['designer-requests/index', 'section' => $key]) ?>"><?= $item ?></a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>

<?php endif; ?>
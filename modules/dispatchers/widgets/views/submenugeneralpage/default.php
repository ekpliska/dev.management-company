<?php

    use yii\helpers\Url;

/* 
 * Навигационное меню, Диспетчеры, Главная страница
 */
$array_controllers = ['requests', 'dispatchers'];
$current_controller = Yii::$app->controller->id;
?>
<?php if (in_array($current_controller, $array_controllers) && Yii::$app->controller->action->id == 'index') : ?>

    <?php $current_block = Yii::$app->controller->actionParams['block']; ?>

    <?php if (isset($general_navbar)) : ?>
    <div class="container-fluid navbar-general-page text-center menu_sub-bar">
        <ul class="nav nav-pills navbar__pills">
            <?php foreach ($general_navbar as $key => $item) : ?>
                <li class="nav-item <?= ($current_block == $key) ? 'active' : '' ?>">
                    <a href="<?= Url::to(["{$current_controller}/index", 'block' => $key]) ?>">
                        <?= $item ?>
                        <span class="menu_sub-bar__span">
                            <?= $key === 'requests' ? $count_requests : $count_paid_requests ?>
                        </span>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php endif; ?>

<?php endif; ?>
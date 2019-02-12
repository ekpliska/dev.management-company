<?php

    use yii\helpers\Url;

/* 
 * Навигационное меню, Диспетчеры, Главная страница
 */
?>
<?php if (Yii::$app->controller->id == 'dispatchers' && Yii::$app->controller->action->id == 'index') : ?>

    <?php $current_block = Yii::$app->controller->actionParams['block']; ?>

    <?php if (isset($general_navbar)) : ?>
    <div class="container-fluid navbar-general-page text-center menu_sub-bar">
        <ul class="nav nav-pills navbar__pills">
            <?php foreach ($general_navbar as $key => $item) : ?>
                <li class="nav-item <?= ($current_block == $key) ? 'active' : '' ?>">
                    <a href="<?= Url::to(['dispatchers/index', 'block' => $key]) ?>">
                        <?= $item ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php endif; ?>

<?php endif; ?>
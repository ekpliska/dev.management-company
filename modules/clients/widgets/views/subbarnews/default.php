<?php

    use yii\helpers\Url;

/* 
 * Новостные рубрики, главная страница новостей
 */
?>

<?php $current_block = Yii::$app->controller->actionParams['block']; ?>
<?php if (isset($general_navbar)) : ?>
<div class="tabbable-line">
<ul class="nav nav-tabs">
    <?php foreach ($general_navbar as $key => $item) : ?>
    <li class="<?= ($current_block == $key) ? 'active' : '' ?>">
        <a href="<?= Url::to(['news/index', 'block' => $key]) ?>">
            <?= $item ?>
        </a>
    </li>
    <?php endforeach; ?>
</ul>
</div>
<?php endif; ?>

<?php /*
<?php $current_block = Yii::$app->controller->actionParams['block']; ?>
<?php if (isset($general_navbar)) : ?>
    <div class="navbar-general-page text-center menu_sub-bar">
        <ul class="nav nav-pills navbar__pills">
            <?php foreach ($general_navbar as $key => $item) : ?>
                <li class="nav-item <?= ($current_block == $key) ? 'active' : '' ?>">
                    <a href="<?= Url::to(['news/index', 'block' => $key]) ?>" 
                       class="<?= ($key == 'special_offers') ? 'central-block' : '' ?>">
                        <?= $item ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; */ ?>

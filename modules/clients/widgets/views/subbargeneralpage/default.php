<?php

    use yii\helpers\Url;

/* 
 * Вывод новостей в личном кабинете собственника
 */
?>
<?php if (Yii::$app->controller->id == 'clients' && Yii::$app->controller->action->id == 'index') : ?>

    <?php $current_block = Yii::$app->controller->actionParams['block']; ?>

    <?php if (isset($general_navbar)) : ?>
    <div class="container-fluid navbar-general-page text-center">
        <ul class="nav nav-pills navbar__pills">
            <?php foreach ($general_navbar as $key => $item) : ?>
                <li class="nav-item <?= ($current_block == $key) ? 'active' : '' ?>">
                    <a href="<?= Url::to(['clients/index', 'block' => $key]) ?>" 
                       class="<?= ($key == 'special_offers') ? 'central-block' : '' ?>">
                        <?= $item ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php endif; ?>

<?php endif; ?>
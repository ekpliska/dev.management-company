<?php

    use yii\helpers\Html;
    use yii\helpers\Url;
    use app\helpers\FormatHelpers;

/* 
 * Вывод новостей в личном кабинете собственника
 */
?>

<?php if (Yii::$app->controller->id == 'news' && Yii::$app->controller->action->id == 'index') : ?>

    <?php $rubric_link = Yii::$app->controller->actionParams['rubric']; ?>

    <?php if (isset($rubrics) && count($rubrics)) : ?>
    <!--<nav class="navbar navbar-dark menu-collapsed-nav justify-content-between navbar-expand-sm p-0 carousel-item d-block">-->
    <nav class="navbar nav-pills mx-auto text-center justify-content-center nav-tref-potty">
        <ul class="nav nav-pills mx-auto text-center justify-content-center">
            <?php foreach ($rubrics as $key => $rubric) : ?>
                <li class="nav-item">
                    <a href="<?= Url::to(['news/index', 'rubric' => $key]) ?>" 
                       class="nav-link 
                            submenu-nav-link 
                            <?= ($rubric_link == $key) ? 'active' : '' ?> 
                            <?= ($key == 'important_information') ? ' no-border-right no-border-left' : '' ?>
                            <?= ($key == 'special_offers') ? ' right-rubrick' : '123' ?>"
                    >
                        <?= $rubric ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </nav>
    <?php endif; ?>

<?php endif; ?>
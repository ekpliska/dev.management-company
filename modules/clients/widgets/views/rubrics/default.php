<?php

    use yii\helpers\Html;
    use yii\helpers\Url;
    use app\helpers\FormatHelpers;

/* 
 * Вывод новостей в личном кабинете собственника
 */
$rubric_link = Yii::$app->controller->actionParams['rubric'];
?>

<?php if (Yii::$app->controller->id == 'clients' && Yii::$app->controller->action->id == 'index') : ?>

    <?php if (isset($rubrics) && count($rubrics)) : ?>
    <nav class="navbar navbar-dark menu-collapsed-nav justify-content-between navbar-expand-sm p-0 carousel-item d-block">
        <ul class="nav nav-pills mx-auto text-center justify-content-center">
            <?php foreach ($rubrics as $key => $rubric) : ?>
                <li class="nav-item">
                    <a href="<?= Url::to(['clients/index', 'rubric' => $key]) ?>" 
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
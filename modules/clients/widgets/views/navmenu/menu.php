<?php

    use yii\helpers\Url;
    use yii\helpers\Html;

/* 
 * Вывод пунктов меню
 */
$count = 0;
$controller = Yii::$app->controller->id;
?>

<div class="list-group">
    <?php foreach ($menu_array as $key => $item) : ?>
        <a href="<?= Url::to([$item['link']]) ?>" class="list-group-item">
            <?= $item['name'] ?>
        </a>
    <?php endforeach; ?>
</div>
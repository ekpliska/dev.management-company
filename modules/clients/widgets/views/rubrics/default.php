<?php

    use yii\helpers\Html;
    use yii\helpers\Url;
    use app\helpers\FormatHelpers;

/* 
 * Вывод новостей в личном кабинете собственника
 */
$rubric_link = Yii::$app->controller->actionParams['rubric'];
//echo '<pre>';
//var_dump($rubric_link); die();
?>

<?php if (isset($rubrics) && count($rubrics)) : ?>
<ul class="pager">
    <?php foreach ($rubrics as $key => $rubric) : ?>
        <li class="<?= $rubric_link == $key ? 'disabled' : '' ?>">
            <a href="<?= Url::to(['clients/index', 'rubric' => $key]) ?>">
                <?= $rubric ?>
            </a>
        </li>
    <?php endforeach; ?>
</ul>
<?php endif; ?>
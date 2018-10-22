<?php

    use yii\helpers\Html;
    use yii\helpers\Url;
    use app\helpers\FormatHelpers;

/* 
 * Вывод новостей в личном кабинете собственника
 */

?>

<?php if (isset($rubrics) && count($rubrics)) : ?>
<ul class="pager">
    <?php foreach ($rubrics as $key => $rubric) : ?>
        <li>
            <a href="<?= Url::to(['clients/index', 'rubric' => $key]) ?>">
                <?= $rubric ?>
            </a>
        </li>
    <?php endforeach; ?>
</ul>
<?php endif; ?>
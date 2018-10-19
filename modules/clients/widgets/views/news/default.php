<?php

    use yii\helpers\Html;
    use yii\helpers\Url;
    use app\helpers\FormatHelpers;

/* 
 * Вывод новостей в личном кабинете собственника
 */

?>
<?php if (isset($news) && count($news) > 0) : ?>
    <?php foreach ($news as $key => $post) : ?>
    <?php ++$cell ?>
        <div class="col-md-4">
            <a href="<?= Url::to(['news/view', 'slug' => $post['slug']]) ?>">
                <?= Html::img('@web' . $post['news_preview'], ['alt' => $post['news_title'], 'style' => 'width:100%']) ?>
            </a>
            <h4>
                <?= Html::a($post['news_title'], ['news/view', 'slug' => $post['slug']]) ?>
            </h4>
            <?= FormatHelpers::formatDate($post['created_at'], false) ?>
            <p><?= FormatHelpers::shortTextNews($post['news_text']) ?></p>
        </div>

        <?php if (($key + 1) % 3 == 0) : ?>
            <div class="clearfix"></div>
        <?php endif; ?>    
    
    <?php endforeach; ?>    
<?php endif; ?>
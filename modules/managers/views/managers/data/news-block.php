<?php

    use yii\helpers\Html;
    use app\helpers\FormatHelpers;

/* 
 * Новостной блок, Голосования
 */
?>

<?php if (!empty($news_content) && count($news_content) > 0) : ?>
    <div class="manager-main__general-left__content">
        <?php foreach ($news_content as $key => $post) : ?>
            <div class="__content-news">
                <?= FormatHelpers::formatUrlNewsOrVote($post['news_title'], $post['slug']) ?>
                <p class="__content-date"><?= Yii::$app->formatter->asDate($post['created_at'], 'long') ?></p>
                <div class="__content-news-item">
                    <div class="col-lg-4 col-md-12 col-sm-2 col-xs-6 __content-image">
                        <?= Html::img("{$post['news_preview']}") ?>
                    </div>
                    <div class="col-lg-8 col-md-12 col-sm-10 col-xs-6 __content-text">
                        <?= FormatHelpers::shortTextNews($post['news_text'], 10) ?>
                    </div>                            
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <div class="notice info">
        <p>Публикации еще не создавались.</p>
    </div>
<?php endif; ?>
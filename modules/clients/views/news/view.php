<?php

    use yii\helpers\Html;
    use app\helpers\FormatHelpers;

/* 
 * Просмотр отдельной новости
 */
$this->title = Yii::$app->params['site-name'] .  $news['news_title'];
?>

<div class="news-conteiner">
    <h2 class="news-conteiner__title">
        <?= $news['news_title'] ?>
    </h2>
    
    <ul class="list-inline post_details">
        <li class="post_date">
            <?= FormatHelpers::formatDate($news['created_at'], true, 1) ?>
        </li>
        <li class="post_rubric">
            <?= $news['rubric']['rubrics_name'] ?>
        </li>
        <?php if (!empty($news['news_partner_id'])) : ?>
        <li class="partners-info">
            <span>От</span>&nbsp;
            <?= Html::img(Yii::getAlias('@web') . $news['partner']['partners_logo'], [
                    'class' => '', 
                    'alt' => $news['partner']['partners_name']]) ?>
            &nbsp;<span><?= $news['partner']['partners_name'] ?></span>
        </li>
        <?php endif; ?>
        <li class="all_news">
            <?= Html::a('Все новости', ['news/index']) ?>
        </li>
    </ul>
    
    <div class="news-conteiner__image">
        <?= Html::img(Yii::getAlias('@web') . $news['news_preview'], [
                'class' => 'news-prview', 
                'alt' => $news['news_title']]); ?>
    </div>
    
    <div class="news-conteiner__content">
        <?= $news['news_text'] ?>
    </div>
    
    <?php if (isset($files) && count($files)) : ?>
        <div class="load-news-documents">
            <h4><span>Вложения</span></h4>
                <?php foreach ($files as $file) : ?>
                    <p class="load-documents">
                        <?= FormatHelpers::formatUrlByDoc($file['name'], $file['filePath']) ?>
                    </p>
                <?php endforeach; ?>
        </div>
    <?php endif; ?>
    
</div>
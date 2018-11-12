<?php

    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\widgets\LinkPager;
    use app\helpers\FormatHelpers;    

/*
 * Главная страница личного кабинета Собственника
 */    
$this->title ="Главная страница";
?>
<div class="row news-list">
    <?php if (isset($news) && count($news) > 0) : ?>
        <?php foreach ($news as $key => $post) : ?>

            <div class="col card news-card-preview box-shadow">
                <?= Html::img('@web' . $post['news_preview'], [
                    'alt' => $post['news_title'], 
                    'class' => 'card-img-top news-card-img-top-preview']) ?>

                <h5 class="news-card-preview-h">
                    <?= Html::a($post['news_title'], ['news/view-news', 'slug' => $post['slug']]) ?>
                </h5>

                <h5 class="news-card-preview-date">
                    <?= FormatHelpers::formatDate($post['created_at'], false) ?>
                </h5>

                <div class="card-body m-0 p-0 news-card-preview-body">
                    <p class="card-text news-card-preview-text ">
                        <?= FormatHelpers::shortTextNews($post['news_text']) ?>
                    </p>
                    <div class=" d-flex justify-content-around align-items-center">
                    </div>
                </div>
            </div>
    
            <?php if (($key + 1) % 3 == 0) : ?>
                <div class="w-100"></div>
            <?php endif; ?>
        <?php endforeach; ?>    
    <?php endif; ?> 
</div>

<?php /*
<div class="row pagination-news">
    <?= LinkPager::widget([
            'pagination' => $pages]); 
    ?>
</div>
 */ ?>

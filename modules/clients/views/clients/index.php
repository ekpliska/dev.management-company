<?php

    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\widgets\LinkPager;
    use app\helpers\FormatHelpers;    

$this->title ="Главная страница"
?>

<div class="row">
    <?php if (isset($news) && count($news) > 0) : ?>
        <?php foreach ($news as $key => $post) : ?>

            <div class="card news-card-preview  box-shadow">
                <?= Html::img('@web' . $post['news_preview'], [
                    'alt' => $post['news_title'], 
                    'class' => 'card-img-top news-card-img-top-preview']) ?>

                <h5 class="news-card-preview-h">
                    <?= $post['news_title'] ?>
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
        <?php endforeach; ?>    
    <?php endif; ?>   
</div>

<div class="row pagination-news">
    <?= LinkPager::widget([
            'pagination' => $pages]); 
    ?>
</div>

<?php /*
<div class="clients-default-index">
    
    <h1><?= $this->title ?></h1>
    
    <?= Rubrics::widget() ?>
    
    <?php if (isset($news) && count($news) > 0) : ?>
        <?php foreach ($news as $key => $post) : ?>
            <div class="col-md-4">
                <a href="<?= Url::to(['clients/view-news', 'slug' => $post['slug']]) ?>">
                    <?= Html::img('@web' . $post['news_preview'], ['alt' => $post['news_title'], 'style' => 'width:100%']) ?>
                </a>
                
                <h4>
                    <?= Html::a($post['news_title'], ['clients/view-news', 'slug' => $post['slug']]) ?>
                    <?php if ($post['isAdvert']) : ?>
                        <span class="label label-danger">Реклама</span>
                        <?= $post['partners_name'] ?>
                    <?php endif; ?>
                </h4>
                
                <?= FormatHelpers::formatDate($post['created_at'], false) ?>
                <p><?= FormatHelpers::shortTextNews($post['news_text']) ?></p>
            </div>

            <?php if (($key + 1) % 3 == 0) : ?>
                <div class="clearfix"></div>
            <?php endif; ?>    

        <?php endforeach; ?>    
    <?php endif; ?>    
        
</div>
 */ ?>
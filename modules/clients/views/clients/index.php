<?php

    use yii\helpers\Html;
    use yii\helpers\Url;
    use app\helpers\FormatHelpers;
    use app\modules\clients\widgets\Rubrics;

$this->title ="Главная страница"
?>

<div class="clients-default-index">
    
    <h1><?= $this->title ?></h1>
    
    <?= Rubrics::widget() ?>
    
    <?php /* Рубрики */ ?>    
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
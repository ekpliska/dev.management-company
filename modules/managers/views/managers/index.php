<?php
    
    use yii\widgets\Breadcrumbs;
    use yii\helpers\Html;
    use yii\helpers\Url;
    use app\helpers\FormatHelpers;

/*
 * Администраторы
 */    
    
$this->title = Yii::$app->params['site-name-manager'] .  'Главная';
$this->params['breadcrumbs'][] = 'Главная';
?>

<div class="manager-main">

    <?= Breadcrumbs::widget([
            'homeLink' => ['label' => 'ELSA | Администратор', 'url' => ['managers/index']],
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
    ]) ?>
    
    <div class="row manager-main__general">
        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 manager-main__general-left">
            <h4>
                Новости, Голосование
                <div class="dropdown settings-news">
                    <button class="dropdown-toggle" type="button" data-toggle="dropdown">+</button>
                    <ul class="dropdown-menu">
                      <li><a href="<?= Url::to(['news/create']) ?>">Добавить публикацию</a></li>
                      <li><a href="<?= Url::to(['voting/create']) ?>">Добавить голосование</a></li>
                    </ul>
                </div>
            </h4>
            <?php if (!empty($news_content) && count($news_content) > 0) : ?>
            <div class="manager-main__general-left__content">
                <?php foreach ($news_content as $key => $post) : ?>
                    <div class="__content-news">
                        <?= Html::a($post['news_title'], ['/'], ['class' => 'title']) ?>
                        <p class="__content-date"><?= Yii::$app->formatter->asDate($post['created_at'], 'long') ?></p>
                        <div class="__content-news-item">
                            <div class="col-md-4 __content-image">
                                <?= Html::img("/web/{$post['news_preview']}") ?>
                            </div>
                            <div class="col-md-8 __content-text">
                                <?= FormatHelpers::shortTextNews($post['news_text'], 25) ?>
                            </div>                            
                        </div>
                        
                    </div>
                <?php endforeach; ?>
            </div>
            <?php else: ?>
                <?= '=(' ?>
            <?php endif; ?>
        </div>
        <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12 manager-main__general-rigth">
            #TODO
        </div>
    </div>
    
</div>
<?php
    
    use yii\widgets\Breadcrumbs;
    use yii\helpers\Url;

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
        <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12 manager-main__general-left">
            <h4>
                Новости, Голосование
                <?php if (Yii::$app->user->can('NewsEdit') && Yii::$app->user->can('VotingsEdit')) : ?>
                <div class="dropdown settings-news">
                    <button class="dropdown-toggle" type="button" data-toggle="dropdown">+</button>
                    <ul class="dropdown-menu">
                      <li><a href="<?= Url::to(['news/create']) ?>">Добавить публикацию</a></li>
                      <li><a href="<?= Url::to(['voting/create']) ?>">Добавить голосование</a></li>
                    </ul>
                </div>
                <?php endif; ?>
            </h4>
            <?= $this->render('data/news-block', ['news_content' => $news_content]) ?>
        </div>
        <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12 manager-main__general-rigth">
            <div class="general-rigth__welcome">
                <h4>Добрый день: <?= Yii::$app->userProfileCompany->fullNameEmployee ?></h4>
                <h4>Сегодня: <?= Yii::$app->formatter->asDate(time(), 'full') ?></h4>
                <hr />
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 general-right__request">
                    <h4>
                        Заявки
                        <span class="pull-right"><?= count($request_list) ?></span>
                    </h4>
                    <?= $this->render('data/requests-block', ['request_list' => $request_list]) ?>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 general-right__request">
                    <h4>
                        Платые услуги
                        <span class="pull-right"><?= count($paid_request_list) ?></span>
                    </h4>
                    <?= $this->render('data/paid-requests-block', ['paid_request_list' => $paid_request_list]) ?>
                </div>
            </div>
        </div>
    </div>
    
</div>
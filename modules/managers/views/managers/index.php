<?php
    
    use yii\widgets\Breadcrumbs;
    use yii\helpers\Url;
    use yii\helpers\Html;
    use app\modules\managers\widgets\StatisticBar;

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
        
        <div class="col-lg-9 manager-main__general__left">
            <div class="row top_bar">
                <?= $this->render('data/statistic-bar', [
                        'count_request' => isset($request_list) ? count($request_list) : 0,
                        'count_paid_request' => isset($paid_request_list) ? count($paid_request_list) : 0,
                        'count_active_vote' => 0,
                ]) ?>
            </div>
            
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-6">
                    <div class="__request_block-request">
                        <div class="__request_block-title">
                            <h5>
                                Заявки
                            </h5>
                        </div>
                        <div class="__request_block-content">
                            content
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-6">
                    <div class="__request_block-request">
                        <div class="__request_block-title">
                            <h5>
                                Платные услуги
                            </h5>
                        </div>
                        <div class="__request_block-content">
                            content
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 manager-main__general__right">
            <div class="today_block">
                <div class="today_block__day">
                    <p>
                        <?= Yii::$app->formatter->asDate(time(), 'd') ?>
                    </p>
                    <p><?= Yii::$app->formatter->asDate(time(), 'MMMM, Y') ?></p>
                </div>
                <div class="today_block__user">
                    <p>
                        Добрый день, 
                        <?= Html::img(Yii::$app->userProfileCompany->photo) ?>
                        <?= Yii::$app->userProfileCompany->fullNameEmployee ?>
                    </p>
                </div>
            </div>
            
            <div class="new_user">
                <div class="__title">
                    <h5>
                        Новые пользователи
                    </h5>
                </div>
                <div class="__content">
                    
                </div>
            </div>
            
            <div class="active_vote">
                <div class="__title">
                    <h5>
                        Активные опросы
                    </h5>
                </div>
                <div class="__content">
                    active_vote
                </div>
            </div>

            <div class="last_news">
                <div class="__title">
                    <h5>
                        Последние новости
                    </h5>
                </div>
                <div class="__content">
                    last_news
                </div>                                    
            </div>
            
        </div>
        
        
<?php /*         
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
*/ 
?>
    </div>
</div>
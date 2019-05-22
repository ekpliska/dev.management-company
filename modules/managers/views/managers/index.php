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
        
        <div class="col-lg-9 col-md-12 col-sm-12 col-xs-12 manager-main__general__left">
            <div class="row top_bar">
                <?= $this->render('data/statistic-bar', [
                        'count_request' => isset($request_list) ? count($request_list) : 0,
                        'count_paid_request' => isset($paid_request_list) ? count($paid_request_list) : 0,
                        'count_active_vote' => isset($active_vote) ? count($active_vote) : 0,
                ]) ?>
            </div>
            
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <div class="__request_block-request">
                        <div class="__request_block-title">
                            <h5>
                                Заявки
                            </h5>
                        </div>
                        <?= $this->render('data/requests-block', ['request_list' => $request_list]) ?>
                    </div>
                </div>
                
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <div class="__request_block-request">
                        <div class="__request_block-title">
                            <h5>
                                Платные услуги
                            </h5>
                        </div>
                        <?= $this->render('data/paid-requests-block', ['paid_request_list' => $paid_request_list]) ?>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12 manager-main__general__right">
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
                <?= $this->render('data/new-users', ['user_lists' => $user_lists]) ?>
            </div>
            
            <div class="active_item">
                <?= $this->render('data/active-vote', ['active_vote' => $active_vote]) ?>
            </div>

            <div class="active_item">
                <?= $this->render('data/last-news', ['news_content' => $news_content]) ?>
            </div>
            
        </div>
    
    </div>
</div>
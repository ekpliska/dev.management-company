<?php

    use yii\helpers\Html;
    use yii\widgets\Breadcrumbs;

/*
 * Диспетчер, главная страница
 */
$this->title = Yii::$app->params['site-name-manager'] .  'Главная';
$this->params['breadcrumbs'][] = 'Главная';
?>


<div class="dispatcher-main-with-sub-general">

    <?= Breadcrumbs::widget([
            'homeLink' => ['label' => 'ELSA | Диспетчер', 'url' => ['dispatchers/index']],
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
    ]) ?>
    
    <div class="row dispatcher__genaral-page">
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="dispatcher__genaral-page__notice">
                <?= $this->render('notice-block/notice_requests', ['user_lists' => $user_lists]) ?>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <div class="dispatcher__genaral-page__request" id="request_lists">
                <?= $this->render('request-block/requests_list', ['user_lists' => $user_lists]) ?>
            </div>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
            <div class="dispatcher__genaral-page__news-btn">
                <?= Html::a('Создать публикацию', ['/'], ['class' => 'news-block__create-link']) ?>
            </div>
            <div class="dispatcher__genaral-page__news-block news-block__margin">
                <div class="dispatcher__genaral-page__news-content">
                    <?= $this->render('data/news_block', ['news_lists' => $news_lists]) ?>
                </div>               
            </div>
        </div>
    </div>
    
    <?= Html::a('', ['employee-form/index', 'new_employee' => 'administrator'], ['class' => 'create-request-btn margin-btn']) ?>
    
</div>


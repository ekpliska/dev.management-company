<?php

    use yii\helpers\Html;
    use yii\widgets\Breadcrumbs;

/*
 * Диспетчер, главная страница
 */
$this->title = Yii::$app->params['site-name-dispatcher'] .  'Главная';
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
                <?= $this->render("notice-block/notice_{$block}", ['user_lists' => $user_lists]) ?>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <div class="dispatcher__genaral-page__request" id="request_lists">
                <?= $this->render("request-block/{$block}_list", [
                        'user_lists' => $user_lists,
                        'status_list' => $status_list]) ?>
            </div>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
            <div class="dispatcher__genaral-page__news-btn">
                <?= Html::a('Создать публикацию', ['news/create'], ['class' => 'news-block__create-link']) ?>
            </div>
            <div class="dispatcher__genaral-page__news-block news-block__margin">
                <div class="dispatcher__genaral-page__news-content">
                    <?= $this->render('data/news_block', ['news_lists' => $news_lists]) ?>
                </div>               
            </div>
        </div>
    </div>
    
    <?php if ($block == 'requests') : ?>
        <?= Html::button('', [
                'class' => 'create-request-btn margin-btn',
                'data-target' => '#create-new-requests',
                'data-toggle' => 'modal',
        ]) ?>
        <?= $this->render('form/create-request', [
                'model' => $model, 
                'type_requests' => $type_requests,
                'flat' => $flat,
        ]) ?>
    <?php endif; ?>

    <?php if ($block == 'paid-requests') : ?>
        <?= Html::button('', [
                'class' => 'create-request-btn margin-btn',
                'data-target' => '#create-new-paid-requests',
                'data-toggle' => 'modal',
        ]) ?>
        <?= $this->render('form/create-paid-request', [
                'model' => $model, 
                'servise_category' => $servise_category,
                'servise_name' => $servise_name,
                'flat' => $flat,
        ]) ?>
    <?php endif; ?>
    
</div>


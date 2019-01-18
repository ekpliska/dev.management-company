<?php

    use yii\helpers\Html;
    use yii\widgets\Breadcrumbs;
    use app\modules\managers\widgets\AlertsShow;

/* 
 * Платные услуги, главная
 */
$this->title = Yii::$app->params['site-name-manager'] .  'Платные услуги';
$this->params['breadcrumbs'][] = 'Платные услуги';
?>

<div class="manager-main-with-sub">
    
    <?= Breadcrumbs::widget([
            'homeLink' => ['label' => 'ELSA | Администратор', 'url' => ['managers/index']],
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
    ]) ?>
    
    <?= AlertsShow::widget() ?>
    
    <div id="requests-result">
        <?= $this->render('data/grid_paid_requests', [
                'paid_requests' => $paid_requests
        ]) ?>
    </div>
    
    <?= Html::button('', [
            'class' => 'create-request-btn',
            'data-target' => '#create-new-paid-requests',
            'data-toggle' => 'modal',
        ]) ?>

    <?= $this->render('form/create_paid_request', [
            'model' => $model,
            'servise_category' => $servise_category,
            'servise_name' => $servise_name,
            'flat' => $flat,]) ?></div>
    
</div>

<?php /*
<div class="managers-default-index">
    <h1><?= $this->title ?></h1>
    <hr />
    
    <?= AlertsShow::widget() ?>
    
    <?= Html::button('Заявка (+)', [
        'class' => 'btn btn-success btn-sm create-paid-request',
        'data-target' => '#create-new-paid-requests',
        'data-toggle' => 'modal']) ?>
    
    <hr />
    
    <?= $this->render('data/grid_paid_requests', [
        'paid_requests' => $paid_requests
    ]) ?>
    
</div>

<?= $this->render('form/create_paid_request', [
        'model' => $model,
        'servise_category' => $servise_category,
        'servise_name' => $servise_name,
        'flat' => $flat,]) ?></div>
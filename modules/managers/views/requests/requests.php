<?php

    use yii\helpers\Html;
    use yii\widgets\Breadcrumbs;
    use app\modules\managers\widgets\AlertsShow;

/* 
 * Завяки, главная
 */
$this->title = Yii::$app->params['site-name-manager'] .  'Заявки';
$this->params['breadcrumbs'][] = 'Заявки';
?>

<div class="manager-main-with-sub">
    
    <?= Breadcrumbs::widget([
            'homeLink' => ['label' => 'ELSA | Администратор', 'url' => ['managers/index']],
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
    ]) ?>
    
    <?= AlertsShow::widget() ?>
    
    <div id="requests-result">
        <?= $this->render('data/grid_requests', [
                'requests' => $requests
        ]) ?>
    </div>
    
</div>

<?php /*
<div class="managers-default-index">
    <h1><?= $this->title ?></h1>
    <hr />
    
    <?= AlertsShow::widget() ?>
    
    <?= Html::button('Заявка (+)', [
        'class' => 'btn btn-success btn-sm create-request',
        'data-target' => '#create-new-requests',
        'data-toggle' => 'modal']) ?>
    
    <hr />
    
    <?= $this->render('data/grid_requests', [
        'requests' => $requests
    ]) ?>
    
</div>

<?= $this->render('form/create_request', [
        'model' => $model,
        'type_request' => $type_request,
        'flat' => $flat,]) ?>
 * 
 * 
 */ ?>
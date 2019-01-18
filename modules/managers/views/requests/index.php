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
    
    <?= Html::button('', [
            'class' => 'create-request-btn',
            'data-target' => '#create-new-requests',
            'data-toggle' => 'modal',
        ]) ?>

    <?= $this->render('form/create_request', [
            'model' => $model, 
            'type_requests' => $type_requests,
            'flat' => $flat,
        ]) ?>
    
</div>
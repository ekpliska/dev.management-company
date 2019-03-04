<?php

    use yii\helpers\Html;
    use yii\widgets\Breadcrumbs;
    use app\modules\managers\widgets\AlertsShow;
    use app\modules\managers\widgets\ModalWindowsManager;

/* 
 * Завяки, главная
 */
$this->title = Yii::$app->params['site-name-manager'] .  'Заявки';
$this->params['breadcrumbs'][] = 'Заявки';
?>

<?= $this->render('form/_search', [
        'search_model' => $search_model,
        'type_requests' => $type_requests,
        'specialist_lists' => $specialist_lists,
]) ?>

<div class="manager-main-with-sub _sub-910">
    
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

    <?php if (Yii::$app->user->can('RequestsEdit')) : ?>
    
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
    
    <?php endif; ?>
</div>

<?= ModalWindowsManager::widget(['modal_view' => 'delete_request_modal']) ?>
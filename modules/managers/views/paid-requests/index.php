<?php

    use yii\helpers\Html;
    use yii\widgets\Breadcrumbs;
    use app\modules\managers\widgets\AlertsShow;
    use app\modules\managers\widgets\ModalWindowsManager;

/* 
 * Платные услуги, главная
 */
$this->title = Yii::$app->params['site-name-manager'] .  'Платные услуги';
$this->params['breadcrumbs'][] = 'Платные услуги';
?>

<?= $this->render('form/_search', [
        'search_model' => $search_model,
        'name_services' => $name_services,
        'specialist_lists' => $specialist_lists,
]) ?>

<div class="manager-main-with-sub _sub-910">
    
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
<?= ModalWindowsManager::widget(['modal_view' => 'delete_request_modal']) ?>
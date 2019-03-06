<?php

    use yii\helpers\Html;
    use yii\widgets\Breadcrumbs;

/* 
 * Завяки, главная
 */
$this->title = Yii::$app->params['site-name-dispatcher'] . 'Заявки';
$this->params['breadcrumbs'][] = 'Заявки';
?>

<?= $this->render("form/_search-{$block}", [
        'search_model' => $search_model,
        'type_requests' => $type_requests,
        'name_services' => $name_services,
        'specialist_lists' => $specialist_lists,
]) ?>

<div class="dispatcher-main-with-sub-general margin-search-panel">
    
    <?= Breadcrumbs::widget([
            'homeLink' => ['label' => 'ELSA | Диспетчер', 'url' => ['dispatchers/index']],
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
    ]) ?>
    
    <div id="requests-result">
        <?= $this->render("data/grid_{$block}", [
                'results' => $results,
        ]) ?>
    </div>
    
    <?php if ($block == 'requests') : ?>
        <?= Html::button('', [
                'class' => 'create-request-btn',
                'data-target' => '#create-new-requests',
                'data-toggle' => 'modal',
            ]) ?>
        <?= $this->render('form/create_request', [
                'model' => $model,
                'type_requests' => $type_requests,
                'flat' => $flat,
            ])
        ?>
    <?php endif; ?>
    
    <?php if ($block == 'paid-requests') : ?>
        <?= Html::button('', [
                'class' => 'create-request-btn',
                'data-target' => '#create-new-paid-requests',
                'data-toggle' => 'modal',
            ]) ?>
        <?= $this->render('form/create-paid-request', [
                'model' => $model, 
                'servise_category' => $servise_category,
                'servise_name' => [],
                'flat' => $flat,
        ]) ?>
    <?php endif; ?>
</div>

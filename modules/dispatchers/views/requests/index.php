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

<?= $this->render('form/_search-request', [
        'search_model' => $search_model,
        'type_requests' => $type_requests,
        'specialist_lists' => $specialist_lists,
]) ?>

<div class="dispatcher-main-with-sub-general margin-search-panel">
    
    <?= Breadcrumbs::widget([
            'homeLink' => ['label' => 'ELSA | Администратор', 'url' => ['dispatchers/index']],
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
    ]) ?>
    
    <div id="requests-result">
        <?= $this->render("data/grid_{$block}", [
                'results' => $results,
        ]) ?>
    </div>
    
    <?= Html::button('', [
            'class' => 'create-request-btn',
            'data-target' => '#create-new-requests',
            'data-toggle' => 'modal',
        ]) ?>

</div>

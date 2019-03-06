<?php

    use yii\widgets\Breadcrumbs;
    use app\modules\managers\widgets\ModalWindowsManager;
    use app\modules\managers\widgets\AlertsShow;

/* 
 * Собственники
 */

$this->title = Yii::$app->params['site-name-manager'] .  'Собственники';
$this->params['breadcrumbs'][] = 'Собственники';
?>

<?= $this->render('_form/_search', ['model' => $model]) ?>

<div class="manager-main-with-sub">

    <?= Breadcrumbs::widget([
            'homeLink' => ['label' => 'ELSA | Администратор', 'url' => ['managers/index']],
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
    ]) ?>
    
    <?= AlertsShow::widget() ?>
    
    <?= $this->render('data/grid', ['client_list' => $client_list]) ?>
</div>

<?= ModalWindowsManager::widget(['modal_view' => 'delete_clients']) ?>
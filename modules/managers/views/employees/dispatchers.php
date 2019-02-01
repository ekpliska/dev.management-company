<?php

    use yii\helpers\Html;
    use yii\widgets\Breadcrumbs;
    use app\modules\managers\widgets\AlertsShow;
    use app\modules\managers\widgets\ModalWindowsManager;

/* 
 * Диспетчеры
 */

$this->title = Yii::$app->params['site-name-manager'] .  'Диспетчеры';
$this->params['breadcrumbs'][] = 'Диспетчеры';
?>

<?= $this->render('form/_search', [
        'model' => $model,
        'departments' => $departments,
        'posts' => $posts,
]) ?>

<div class="manager-main-with-sub">

    <?= Breadcrumbs::widget([
            'homeLink' => ['label' => 'ELSA | Администратор', 'url' => ['managers/index']],
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
    ]) ?>
    
    <?= AlertsShow::widget() ?>
    
    <?= $this->render('data/grid_dispatchers', ['dispatchers' => $dispatchers]) ?>
    
    <?= Html::a('', ['employee-form/index', 'new_employee' => 'dispatcher'], ['class' => 'create-request-btn']) ?>
    
</div>

<?= ModalWindowsManager::widget(['modal_view' => 'delete_employee']) ?>
<?php
    
    use yii\widgets\Breadcrumbs;
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
    use app\modules\managers\widgets\AlertsShow;
    use app\modules\managers\widgets\ModalWindowsManager;

/*
 * Администраторы
 */    
    
$this->title = Yii::$app->params['site-name-manager'] .  'Администраторы';
$this->params['breadcrumbs'][] = 'Администраторы';
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
    
    <?= $this->render('data/grid', ['manager_list' => $manager_list]) ?>
    
    <?= Html::a('', ['employee-form/index', 'new_employee' => 'administrator'], ['class' => 'create-request-btn']) ?>
    
</div>

<?= ModalWindowsManager::widget(['modal_view' => 'delete_employee']) ?>
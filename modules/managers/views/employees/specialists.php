<?php

    use app\modules\managers\widgets\AlertsShow;
    use yii\helpers\Html;
    use yii\widgets\Breadcrumbs;
    use app\modules\managers\widgets\ModalWindowsManager;

/* 
 * Специалисты
 */
$this->title = Yii::$app->params['site-name-manager'] .  'Специалисты';
$this->params['breadcrumbs'][] = 'Специалисты';
?>

<?= $this->render('form/_search', [
        'model' => $model,
        'departments' => $departments,
        'posts' => $posts,
]) ?>

<div class="manager-main-with-sub _sub-4">

    <?= Breadcrumbs::widget([
            'homeLink' => ['label' => 'ELSA | Администратор', 'url' => ['managers/index']],
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
    ]) ?>
    
    <?= AlertsShow::widget() ?>
    
    <?= $this->render('data/grid_specialists', ['specialists' => $specialists]) ?>
    
    <?php if (Yii::$app->user->can('EmployeesEdit')) : ?>
        <?= Html::a('', ['employee-form/index', 'new_employee' => 'specialist'], ['class' => 'create-request-btn']) ?>
    <?php endif; ?>
    
</div>

<?= ModalWindowsManager::widget(['modal_view' => 'delete_employee']) ?>
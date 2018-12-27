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

<div class="manager-main-with-sub">

    <?= Breadcrumbs::widget([
            'homeLink' => ['label' => 'ELSA | Администратор', 'url' => ['managers/index']],
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
    ]) ?>
    
    <?= AlertsShow::widget() ?>
    
    <?= $this->render('data/grid_specialists', ['specialists' => $specialists]) ?>
    
    <?= Html::a('', ['employee-form/index', 'new_employee' => 'specialist'], ['class' => 'create-request-btn']) ?>
    
</div>

<?= ModalWindowsManager::widget(['modal_view' => 'delete_employee']) ?>

<?php /*

<div class="dispatchers-default-index">
    <h1><?= $this->title ?></h1>
    
    <?= AlertsShow::widget() ?>
    
    <?= Html::a('Специалисты (+)', ['employers/add-specialist'], ['class' => 'btn btn-success btn-sm']) ?>
    
    <?php
        $form = ActiveForm::begin([
            'id' => 'search-form',
        ]);
    ?>
        
        <?= $form->field($search_model, '_input')
                ->input('text', [
                    'placeHolder' => 'Поиск...',
                    'id' => '_search-specialist',])
                ->label() ?>
    
    <?php ActiveForm::end(); ?>
    
    <hr />
    <?= $this->render('data/grid_specialists', ['specialists' => $specialists]) ?>
</div>

<?= ModalWindowsManager::widget(['modal_view' => 'delete_specialist']) ?>
 * 
 * 
 */?>
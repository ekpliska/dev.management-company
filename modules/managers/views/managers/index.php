<?php
    
    use yii\widgets\Breadcrumbs;
    use yii\helpers\Html;
    use app\modules\managers\widgets\AlertsShow;

/*
 * Администраторы
 */    
    
$this->title = Yii::$app->params['site-name-manager'] .  'Администраторы';
$this->params['breadcrumbs'][] = 'Администраторы';
?>

<div class="manager-main-with-sub">

    <?= Breadcrumbs::widget([
            'homeLink' => ['label' => 'ELSA | Администратор', 'url' => ['managers/index']],
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
    ]) ?>
    
    <?= AlertsShow::widget() ?>
    
    <?= $this->render('data/grid', ['manager_list' => $manager_list]) ?>
    
    <?= Html::a('', ['employee-form/index', 'new_employee' => 'administrator'], ['class' => 'create-request-btn']) ?>
    
</div>
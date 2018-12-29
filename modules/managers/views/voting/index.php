<?php

    use yii\helpers\Html;
    use yii\widgets\Breadcrumbs;
    use app\modules\managers\widgets\ModalWindowsManager;
    use app\modules\managers\widgets\AlertsShow;

/* 
 * Голосование, главная
 */
$this->title = Yii::$app->params['site-name-manager'] .  'Голосование';
$this->params['breadcrumbs'][] = 'Голосование';
?>

<div class="manager-main-with-sub">

    <?= Breadcrumbs::widget([
            'homeLink' => ['label' => 'ELSA | Администратор', 'url' => ['managers/index']],
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
    ]) ?>
    
    <?= AlertsShow::widget() ?>
    
    <div id="_list-res">
        <?= $this->render('data/view_all_voting', ['view_all_voting' => $view_all_voting, 'pages' => $pages]) ?>
    </div>
    
    <?= Html::a('', ['voting/create'], ['class' => 'create-request-btn']) ?>
    
</div>

<?php /*
<div class="managers-default-index">
    <h1><?= $this->title ?></h1>
    <hr />
    
    <?= AlertsShow::widget() ?>
    
    <?= Html::a('Голосование (+)', ['voting/create'], ['class' => 'btn btn-success btn-sm']) ?>
    
    <hr />
    
    <?= $this->render('data/view_all_voting', ['view_all_voting' => $view_all_voting, 'pages' => $pages]) ?>
    
</div>
*/ ?>
<?= ModalWindowsManager::widget(['modal_view' => 'delete_voting']) ?>
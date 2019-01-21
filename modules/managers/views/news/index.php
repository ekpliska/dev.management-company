<?php

    use yii\widgets\Breadcrumbs;
    use yii\helpers\Html;
    use app\modules\managers\widgets\AlertsShow;
    use app\modules\managers\widgets\ModalWindowsManager;

/* 
 * Новости главная страница
 */
$this->title = Yii::$app->params['site-name-manager'] .  'Новости';
$this->params['breadcrumbs'][] = 'Новости';
?>

<div class="manager-main-with-sub-news">

    <?= Breadcrumbs::widget([
            'homeLink' => ['label' => 'ELSA | Администратор', 'url' => ['managers/index']],
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
    ]) ?>
    
    <?= AlertsShow::widget() ?>
    
    <div id="_list-res">
        <?= $this->render("data/grid_{$section}", ["all_{$section}" => $results]) ?>
    </div>
    
    <?= Html::a('', ['news/create'], ['class' => 'create-request-btn']) ?>
    
</div>

<?= ModalWindowsManager::widget(['modal_view' => 'delete_news']) ?>
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

<?= $this->render('form/_search', [
        'search_model' => $search_model,
        'house_lists' => $house_lists,
]) ?>

<div class="manager-main-with-sub __vote-767">

    <?= Breadcrumbs::widget([
            'homeLink' => ['label' => 'ELSA | Администратор', 'url' => ['managers/index']],
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
    ]) ?>
    
    <?= AlertsShow::widget() ?>
    
    <div id="_list-res">
        <?= $this->render('data/view_all_voting', ['view_all_voting' => $view_all_voting, 'pages' => $pages]) ?>
    </div>
    
    <?php if (Yii::$app->user->can('VotingsEdit')) : ?>
        <?= Html::a('', ['voting/create'], ['class' => 'create-request-btn']) ?>
    <?php endif; ?>
    
</div>

<?= ModalWindowsManager::widget(['modal_view' => 'delete_voting']) ?>
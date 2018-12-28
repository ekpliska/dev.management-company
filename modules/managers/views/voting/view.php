<?php

    use yii\helpers\Html;
    use yii\widgets\Breadcrumbs;
    use app\modules\managers\widgets\AlertsShow;
    use app\helpers\FormatHelpers;
    use app\helpers\FormatFullNameUser;
    use app\modules\managers\widgets\ModalWindowsManager;
    
/* 
 * Голосование, создание голосования
 */

$this->title = 'Голосование';
$this->title = Yii::$app->params['site-name-manager'] . 'Голосование';
$this->params['breadcrumbs'][] = ['label' => 'Голосование', 'url' => ['voting/index']];
$this->params['breadcrumbs'][] = $model->voting->voting_title;
?>

<div class="manager-main">
    <?= Breadcrumbs::widget([
            'homeLink' => ['label' => 'ELSA | Администратор', 'url' => ['managers/index']],
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
    ]) ?>

    <?= AlertsShow::widget() ?>
    
    <?= $this->render('form/_form', [
        'model' => $model,
        'type_voting' => $type_voting,
        'houses_array' => $houses_array,
    ]) ?>
    
</div>
<?= ModalWindowsManager::widget(['modal_view' => 'delete_voting']) ?>

<?php /*
<div class="managers-default-index">
    <h1><?= $this->title ?></h1>
    
    <?= AlertsShow::widget() ?>
    
    <hr />    
    <?= $model->voting->getAttributeLabel('created_at') ?>: <?= FormatHelpers::formatDate($model->voting->created_at, true, 0) ?>
    <br />
    <?= $model->voting->getAttributeLabel('updated_at') ?>: <?= FormatHelpers::formatDate($model->voting->updated_at, true, 0) ?>
    <br />
    <?= $model->voting->getAttributeLabel('voting_user_id') ?>: <?= FormatFullNameUser::nameEmployerByUserID($model->voting->voting_user_id) ?>
    <br />
    <?= $model->voting->getAttributeLabel('status') ?>: <?= FormatHelpers::statusNameVoting($model->voting->status) ?>
    <br />
    <?= Html::button('Удалить', [
            'class' => 'btn btn-danger btn-sm',
            'data-toggle' => 'modal',
            'data-target' => '#delete_voting_manager',
            'data-voting' => $model->voting->voting_id]) ?>
    
    <?php if ($model->voting->status !== 1) : ?>
        <?= Html::button('Завершить голосование', [
                'class' => 'btn btn-success btn-sm close_voting_btn',
                'data-toggle' => 'modal',
                'data-target' => '#close_voting',
                'data-voting' => $model->voting->voting_id]) ?>
    <?php endif; ?>
    <hr />
    
    <div id="test"></div>
    <?= $this->render('form/_form', [
            'model' => $model,
            'type_voting' => $type_voting,
            'houses_array' => $houses_array,
    ]) ?>
    
</div>
*/ ?>
<?= ModalWindowsManager::widget(['modal_view' => 'delete_voting']) ?>
<?php

    use yii\helpers\Html;
    use app\modules\managers\widgets\AlertsShow;
    use app\helpers\FormatHelpers;
    use app\helpers\FormatFullNameUser;
    use app\modules\managers\widgets\ModalWindowsManager;
    
/* 
 * Голосование, создание голосования
 */

$this->title = $model->voting->voting_title;
?>
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
    <?= Html::button('Удалить', [
            'class' => 'btn btn-danger btn-sm',
            'data-toggle' => 'modal',
            'data-target' => '#delete_voting_manager',
            'data-voting' => $model->voting->voting_id]) ?>
    <hr />
    
    <?= $this->render('form/_form', [
            'model' => $model,
            'type_voting' => $type_voting,
    ]) ?>
    
</div>

<?= ModalWindowsManager::widget(['modal_view' => 'delete_voting']) ?>
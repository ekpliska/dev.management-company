<?php

    use app\modules\managers\widgets\AlertsShow;
    use app\helpers\FormatHelpers;
    use app\helpers\FormatFullNameUser;
    
/* 
 * Голосование, создание голосования
 */

$this->title = $model->voting->voting_title;
?>
<div class="managers-default-index">
    <h1><?= $this->title ?></h1>
    <hr />
    <?= $model->voting->getAttributeLabel('created_at') ?>: <?= FormatHelpers::formatDate($model->voting->created_at, true, 0) ?>
    <br />
    <?= $model->voting->getAttributeLabel('updated_at') ?>: <?= FormatHelpers::formatDate($model->voting->updated_at, true, 0) ?>
    <br />
    <?= $model->voting->getAttributeLabel('voting_user_id') ?>: <?= FormatFullNameUser::nameEmployerByUserID($model->voting->voting_user_id) ?>
    <hr />
    <?= AlertsShow::widget() ?>
    
    <?= $this->render('form/_form', [
            'model' => $model,
            'type_voting' => $type_voting,
    ]) ?>
    
</div>
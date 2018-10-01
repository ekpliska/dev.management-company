<?php

    use yii\helpers\Html;
    use app\modules\clients\widgets\AlertsShow;

/* 
 * Новости, нлавная страница
 */
$this->title = 'Новости';
?>
<div class="managers-default-index">
    <h1><?= $this->title ?></h1>
    <hr />
    <?= AlertsShow::widget() ?>
    <?= Html::a('Новость (+)', ['news/create'], [
        'class' => 'btn btn-sm btn-success',])
    ?>
    
</div>

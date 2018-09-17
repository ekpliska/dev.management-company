<?php

    use yii\helpers\Html;

/* 
 * Диспетчеры
 */

$this->title = 'Диспетчеры';
?>
<div class="dispatchers-default-index">
    <h1><?= $this->title ?></h1>
    <?= Html::a('Диспетчер (+)', ['dispatchers/add-dispatcher'], ['class' => 'btn btn-success btn-sm']) ?>
    <hr />
</div>
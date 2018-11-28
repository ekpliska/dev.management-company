<?php

    use yii\helpers\Html;
    
/* 
 * Заявки (Обзая страница)
 */
$this->title = 'История услуг';
?>


<div class="paid-requests-history">
    <?= $this->render('data/grid', ['all_orders' => $all_orders]) ?>
</div>
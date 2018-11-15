<?php

    use yii\helpers\Html;
    
/* 
 * Заявки (Обзая страница)
 */
$this->title = 'История услуг';
?>


<div class=table-applications>
    <?= $this->render('data/grid', ['all_orders' => $all_orders]) ?>
</div>

<div class="fixed-bottom req-fixed-bottom-btn-group mx-auto ">
    <?= Html::a('', ['paid-services/index'], ['class' => 'add-req-fixed-btn add-paid-req-btn btn-link']) ?>
</div>
<?php

    use yii\grid\GridView;
    use app\modules\clients\widgets\AlertsShow;

/* 
 * Клиенты
 */

$this->title = 'Клиенты';
?>
<div class="managers-default-index">
    <h1><?= $this->title ?></h1>
    
    <?= AlertsShow::widget() ?>
    
    <?= $this->render('data/grid', ['client_list' => $client_list]) ?>
    
    
</div>
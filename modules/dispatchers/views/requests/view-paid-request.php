<?php

    use yii\widgets\Breadcrumbs;


/* 
 * Просмотр и редактирование заявки
 */
$this->title = "Заявка на платную услугу ID{$request['requests_ident']}";
$this->params['breadcrumbs'][] = ['label' => 'Завяки', 'url' => ['requests/index']];
$this->params['breadcrumbs'][] = "Заявка на платную услугу ID{$request['requests_ident']}";
?>
<div class="dispatcher-main">
    
    <?= Breadcrumbs::widget([
            'homeLink' => ['label' => 'ELSA | Администратор', 'url' => ['managers/index']],
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
    ]) ?>
    
    <div class="requests-view">
        
    </div>
    
</div>
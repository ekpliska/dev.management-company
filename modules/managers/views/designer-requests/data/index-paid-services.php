<?php

    use yii\helpers\Url;

/* 
 * Конструктор заявок, раздел Платные услуги
 */
?>
<div id="_list-res" class="row housing-stock">
    <div class="col-md-5">
        <h4 class="title">Категория</h4>
    </div>
        
    <div class="col-md-7">
        <h4 class="title">Услуга</h4>
    </div>
        
</div>
<div class="dropup action-housing-stock">
    <button class="action-housing-stock__button dropdown-toggle" type="button" data-toggle="dropdown"></button>
    <ul class="dropdown-menu">
        <li><a href="<?= Url::to(['/']) ?>" id="add-category-btn">Добавить категорию</a></li>
        <li><a href="<?= Url::to(['/']) ?>" id="add-service-btn">Добавить услугу</a></li>
    </ul>
</div>    
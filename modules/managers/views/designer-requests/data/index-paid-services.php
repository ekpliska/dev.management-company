<?php

    use yii\helpers\Url;
    use yii\helpers\Html;

/* 
 * Конструктор заявок, раздел Платные услуги
 */
?>
<div class="row designer-block">
    <div class="col-md-5">
        <h4 class="title">Категория</h4>
        <div class="designer-block__search-block">
            <?= Html::input('text', 'search-services', null, ['class' => 'search-block__input', 'placeholder' => 'Поиск']) ?>
        </div>
        <div class="designer-block__lists">
            <ul id="categories-list">
                <li>
                    Adele
                    <span class="close category__delete" data-note="26"><i class="glyphicon glyphicon-trash"></i></span>
                </li>
                <li>
                    Adele
                    <span class="close category__delete" data-note="26"><i class="glyphicon glyphicon-trash"></i></span>
                </li>
                <li>
                    Adele
                    <span class="close category__delete" data-note="26"><i class="glyphicon glyphicon-trash"></i></span>
                </li>
                <li>
                    Adele
                    <span class="close category__delete" data-note="26"><i class="glyphicon glyphicon-trash"></i></span>
                </li>
            </ul>
        </div>
        
    </div>
        
    <div class="col-md-7">
        <h4 class="title">Услуга</h4>
        <div class="designer-block__lists">
            <ul class="services-list">
                <li>
                    Adele <span class="span-count">#TODO</span>
                    <div class="dropdown dropdown__settings-service">
                        <button type="button" class="btn-settings dropdown-toggle" data-toggle="dropdown">
                            <i class="glyphicon glyphicon-option-horizontal"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-setting">
                            <li>Редактировать</li>
                            <li>Удалить услугу</li>
                        </ul>
                    </div>
                </li>
                <li>
                    Adele <span class="span-count">#TODO</span>
                    <div class="dropdown dropdown__settings-service">
                        <button type="button" class="btn-settings dropdown-toggle" data-toggle="dropdown">
                            <i class="glyphicon glyphicon-option-horizontal"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-setting">
                            <li>Редактировать</li>
                            <li>Удалить услугу</li>
                        </ul>
                    </div>
                </li>
            </ul>
        </div>
    </div>
        
</div>

<div class="dropup action-housing-stock">
    <button class="action-housing-stock__button dropdown-toggle" type="button" data-toggle="dropdown"></button>
    <ul class="dropdown-menu">
        <li>
            <?= Html::a('Добавить категорию', ['/'], ['data-target' => '#create-category-modal',
            'data-toggle' => 'modal']) ?>
        </li>
        <li>
            <?= Html::a('Добавить услугу', ['/'], ['data-target' => '#create-service-modal',
            'data-toggle' => 'modal']) ?>
        </li>
    </ul>
</div>
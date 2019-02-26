<?php

    use yii\helpers\Html;

/* 
 * Конструктор заявок, раздел Платные услуги
 */
?>
<div class="row designer-block">
    <div class="col-lg-5 col-md-5 col-sm-6 col-xs-12">
        <h4 class="title">Категория</h4>
        <div class="designer-block__search-block">
            <?= Html::input('text', 'search-services', null, ['id' => 'search-input-designer', 'class' => 'search-block__input', 'placeholder' => 'Поиск']) ?>
        </div>
        <div class="designer-block__lists">
            <?php if ($results['categories']) : ?>
                <ul id="search-lists" class="categories-list">
                    <?php foreach ($results['categories'] as $key_cat => $category) : ?>
                        <li data-record-type="<?= 'category' ?>" data-record="<?= $key_cat ?>" class="<?= $this->context->category_cookie == $key_cat ? 'active-item' : '' ?>">
                            <p><?= $category ?></p>
                            <span class="close category__delete" data-record="<?= $key_cat ?>" data-record-type="category"><i class="glyphicon glyphicon-trash"></i></span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
        
    </div>
        
    <div class="col-lg-7 col-md-7 col-sm-6 col-xs-12">
        <h4 class="title">Услуга</h4>
        <div class="designer-block__lists" id="block__lists-services">
            <?= $this->render('data-category', ['results' => $results['services']]) ?>
        </div>
    </div>
        
</div>

<div class="dropup action-housing-stock">
    <button class="action-housing-stock__button dropdown-toggle" type="button" data-toggle="dropdown"></button>
    <ul class="dropdown-menu">
        <li>
            <a href="javascript:void(0);" data-target="#create-category-modal" data-toggle="modal">Добавить категорию</a>
        </li>
        <li>
            <a href="javascript:void(0);" data-target="#create-service-modal" data-toggle="modal">Добавить услугу</a>
        </li>
    </ul>
</div>
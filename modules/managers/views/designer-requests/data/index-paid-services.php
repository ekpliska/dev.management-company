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
            <?php if ($results['categories']) : ?>
                <ul id="categories-list">
                    <?php foreach ($results['categories'] as $key_cat => $category) : ?>
                        <li data-check-category="<?= $key_cat ?>">
                            <?= $category ?>
                            <span class="close category__delete" data-category="<?= $key_cat ?>"><i class="glyphicon glyphicon-trash"></i></span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
        
    </div>
        
    <div class="col-md-7">
        <h4 class="title">Услуга</h4>
        <div class="designer-block__lists" id="block__lists-services">
            <?= $this->render('services-list', ['services_list' => $services_list]) ?>
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
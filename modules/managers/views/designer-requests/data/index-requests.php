<?php

    use yii\helpers\Html;

/* 
 * Конструктор заявок, раздел Заявки
 */
?>
<div id="_list-res" class="row housing-stock">
    <div class="col-md-5">
        <h4 class="title">Заявки</h4>
        <div class="designer-block__search-block">
            <?= Html::input('text', 'search-services', null, ['class' => 'search-block__input', 'placeholder' => 'Поиск']) ?>
        </div>
        <div class="designer-block__lists">
            <?php if ($results['requests']) : ?>
                <ul id="categories-list">
                    <?php foreach ($results['requests'] as $key_req => $request) : ?>
                        <li data-check-category="<?= $key_req ?>" class="<?= $this->context->request_cookie == $request ? 'active-item' : '' ?>">
                            <?= $request ?>
                            <span class="close request__delete" data-record="<?= $key_req ?>" data-record-type="request"><i class="glyphicon glyphicon-trash"></i></span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    </div>
        
    <div class="col-md-7">
        <h4 class="title">Вопросы</h4>
    </div>
    
</div>

<div class="dropup action-housing-stock">
    <button class="action-housing-stock__button dropdown-toggle" type="button" data-toggle="dropdown"></button>
    <ul class="dropdown-menu">
        <li>
            <?= Html::a('Добавить заявку', ['/'], ['data-target' => '#create-request-modal',
            'data-toggle' => 'modal']) ?>
        </li>
        <li>
            <?= Html::a('Добавить вопрос', ['/'], ['data-target' => '#create-question-modal',
            'data-toggle' => 'modal']) ?>
        </li>
    </ul>
</div>
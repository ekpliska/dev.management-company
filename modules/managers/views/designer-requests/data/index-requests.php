<?php

    use yii\helpers\Html;

/* 
 * Конструктор заявок, раздел Заявки
 */
?>
<div id="_list-res" class="row housing-stock">
    <div class="col-lg-5 col-md-5 col-sm-6 col-xs-12">
        <h4 class="title">Заявки</h4>
        <div class="designer-block__search-block">
            <?= Html::input('text', 'search-services', null, ['id' => 'search-input-designer', 'class' => 'search-block__input', 'placeholder' => 'Поиск']) ?>
        </div>
        <div class="designer-block__lists">
            <?php if ($results['requests']) : ?>
                <ul id="search-lists" class="requests-list">
                    <?php foreach ($results['requests'] as $key_req => $request) : ?>
                        <li data-record-type="<?= 'request' ?>" data-record="<?= $key_req ?>" class="<?= $this->context->request_cookie == $key_req ? 'active-item' : '' ?>">
                            <p><?= $request ?></p>
                            <span class="span-count"><?= "ID {$key_req}" ?></span>
                            <?php if (Yii::$app->user->can('DesignerEdit')) : ?>
                                <span class="close request__delete" data-record="<?= $key_req ?>" data-record-type="request"><i class="glyphicon glyphicon-trash"></i></span>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    </div>
        
    <div class="col-lg-7 col-md-7 col-sm-6 col-xs-12">
        <h4 class="title">Вопросы</h4>
        <div class="designer-block__lists" id="block__lists-services">
            <?= $this->render('data-request', ['results' => $results['questions']]) ?>
        </div>
    </div>
    
</div>

<?php if (Yii::$app->user->can('DesignerEdit')) : ?>
    <div class="dropup action-housing-stock">
        <button class="action-housing-stock__button dropdown-toggle" type="button" data-toggle="dropdown"></button>
        <ul class="dropdown-menu">
            <li>
                <a href="javascript:void(0);" data-target="#create-request-modal" data-toggle="modal">Добавить заявку</a>
            </li>
            <li>
                <a href="javascript:void(0);" data-target="#create-question-modal" data-toggle="modal">Добавить вопрос</a>
            </li>
        </ul>
    </div>
<?php endif; ?>
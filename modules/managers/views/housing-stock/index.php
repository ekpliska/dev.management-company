<?php

    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\bootstrap\Modal;
    use yii\widgets\Breadcrumbs;
    use app\modules\managers\widgets\AlertsShow;
    use app\helpers\FormatHelpers;
    use app\modules\managers\widgets\ModalWindowsManager;

/* 
 * Жилищный фонд, главная
 */
$this->title = Yii::$app->params['site-name-manager'] .  'Жилищный фонд';
$this->params['breadcrumbs'][] = 'Жилищный фонд';
?>

<div class="manager-main">

    <?= Breadcrumbs::widget([
            'homeLink' => ['label' => 'ELSA | Администратор', 'url' => ['managers/index']],
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
    ]) ?>
    
    <?= AlertsShow::widget() ?>
    
    <div id="_list-res" class="row housing-stock">
        <div class="col-lg-4 col-md-4 col-sm-6 housing-stock__description">
            <h4 class="title">Жилой комплекс (Дома)</h4>
            <div class="panel-group" id="accordion">
                <?php foreach ($houses_list as $house) : ?>
                    <div class="panel panel-housing-stock">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a data-toggle="collapse" data-parent="#accordion" href="#house<?= $house['houses_id'] ?>" id="house_link">
                                    <?= $house['houses_name'] ?>
                                </a>
                            </h4>
                            <h4 class="house-adress">
                                <i class="glyphicon glyphicon-map-marker"></i> <?= "Ул. {$house['houses_street']}, д. {$house['houses_number']}" ?>
                            </h4>
                        </div>
                        <div id="house<?= $house['houses_id'] ?>" class="panel-collapse collapse house_accordion <?= $house_cookie == $house['houses_id'] ? 'in' : '' ?>">
                            <div class="panel-body house-description">
                                <span>Описание</span>
                                <p>
                                    <?= $house['houses_description'] ? $house['houses_description'] : '<span>(Описание отсутствует)</span>' ?>
                                </p>
                                
                                <?php if (Yii::$app->user->can('EstatesEdit')) : ?>
                                    <?= Html::a('Редактировать описание', ['update-description', 'house_id' => $house['houses_id']], [
                                            'class' => 'btn-sm',
                                            'id' => 'edit-discription-btn']) ?>
                                <?php endif; ?>
                                
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        
        <div class="col-lg-2 col-md-3 col-sm-6 housing-stock__characteristics">
            <h4 class="title">Характеристики</h4>
            <div id="characteristic_list">
                <?= $this->render('data/characteristics_house', ['characteristics' => $characteristics]) ?>
            </div>
        </div>
        
        <div class="col-lg-4 col-md-5 col-sm-6 housing-stock__flats">
            <div id="flats_list">
                <?= $this->render('data/view_flats', ['flats' => $flats]) ?>
            </div>
        </div>
        
        <div class="col-lg-2 col-md-12 col-sm-6 housing-stock__files">
            <h4 class="title">Вложения</h4>
            <div id="files_list">
                <?= $this->render('data/view_upload_files', ['files' => $files]) ?>
            </div>
        </div>
    </div>
    
    <?php if (Yii::$app->user->can('EstatesEdit')) : ?>
        <div class="dropup action-housing-stock">
            <button class="action-housing-stock__button dropdown-toggle" type="button" data-toggle="dropdown"></button>
            <ul class="dropdown-menu">
                <li><a href="<?= Url::to(['create-characteristic']) ?>" id="add-charact-btn">Добавить характеристику</a></li>
                <li><a href="<?= Url::to(['load-files']) ?>" id="add-files-btn">Добавить вложение</a></li>
            </ul>
        </div>
    <?php endif; ?>
    
</div>

<?php if (Yii::$app->user->can('EstatesEdit')) : ?>
<?php
    /* Модальное окно для редактирования описания дома */
    Modal::begin([
        'id' => 'edit-description-house',
        'header' => 'Редактирование описания дома',
        'closeButton' => [
            'class' => 'close modal-close-btn',
        ],
        'clientOptions' => [
            'backdrop' => 'static', 
            'keyboard' => false,
        ],
    ]);
?>
<?php Modal::end(); ?>

<?php
    /* Модальное окно для добавления новой характеристики */
    Modal::begin([
        'id' => 'add-characteristic-modal-form',
        'header' => 'Добавить характеристику',
        'closeButton' => [
            'class' => 'close modal-close-btn',
        ],
        'clientOptions' => [
            'backdrop' => 'static', 
            'keyboard' => false],
    ]);
?>
<?php Modal::end(); ?>

<?php
    /* Модальное окно для загрузки нового документа */
    Modal::begin([
        'id' => 'add-load-files-modal-form',
        'header' => 'Загрузить вложение',
        'closeButton' => [
            'class' => 'close modal-close-btn',
        ],
        'clientOptions' => [
            'backdrop' => 'static', 
            'keyboard' => false],
    ]);
?>
<?php Modal::end(); ?>

<?php
    /* Модальное окно для загрузки формы установки Статуса "Должник" */
    Modal::begin([
        'id' => 'add-note-modal-form',
        'header' => 'Установка статуса "Должник"',
        'closeButton' => [
            'class' => 'close modal-close-btn',
        ],
        'clientOptions' => [
            'backdrop' => 'static', 
            'keyboard' => false],
    ]);
?>
<?php Modal::end(); ?>

<?= ModalWindowsManager::widget(['modal_view' => 'estate_house']) ?>

<?php endif; ?>
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

<div class="manager-main-with-sub">

    <?= Breadcrumbs::widget([
            'homeLink' => ['label' => 'ELSA | Администратор', 'url' => ['managers/index']],
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
    ]) ?>
    
    <?= AlertsShow::widget() ?>
    
    <div id="_list-res" class="row housing-stock">
        <div class="col-md-4">
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
                                <?= FormatHelpers::formatHousingStosk($house['houses_gis_adress'], $house['houses_number']) ?>
                            </h4>
                        </div>
                        <div id="house<?= $house['houses_id'] ?>" class="panel-collapse collapse house_accordion <?= $house_cookie == $house['houses_id'] ? 'in' : '' ?>">
                            <div class="panel-body house-description">
                                <span>Описание</span>
                                <p>
                                    <?= $house['houses_description'] ? $house['houses_description'] : '<span>(Описание отсутствует)</span>' ?>
                                </p>
                                <?= Html::a('Редактировать описание', ['update-description', 'house_id' => $house['houses_id']], [
                                        'class' => 'btn btn-sm',
                                        'id' => 'edit-discription-btn',
                                ]) ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        
        <div class="col-md-2">
            <h4 class="title">Характеристики</h4>
        </div>
        
        <div class="col-md-4">
            <h4 class="title">Квартиры</h4>
        </div>
        
        <div class="col-md-2">
            <h4 class="title">Вложения</h4>
        </div>
    </div>
    
    <div class="dropup action-housing-stock">
        <button class="action-housing-stock__button dropdown-toggle" type="button" data-toggle="dropdown"></button>
        <ul class="dropdown-menu">
            <li><a href="<?= Url::to(['create-characteristic']) ?>" id="add-charact-btn">Добавить характеристику</a></li>
            <li><a href="<?= Url::to(['load-files']) ?>" id="add-files-btn">Добавить вложение</a></li>
        </ul>
    </div>
    
    
</div>

<?php /*
<div class="managers-default-index">
    <h1><?= $this->title ?></h1>
    <hr />
    
    <?= AlertsShow::widget() ?>
    
    <div class="dropdown">
        <button class="btn btn-success dropdown-toggle" type="button" data-toggle="dropdown">Жилищный фонд (+)
            <span class="caret"></span></button>
            <ul class="dropdown-menu">
                <li><a href="<?= Url::to(['estates/create-house']) ?>">Добавить дом</a></li>
                <li><a href="<?= Url::to(['estates/create-flat']) ?>">Добавить квартиру</a></li>
            </ul>
    </div>
    <div class="dropdown">
        <button class="btn btn-success dropdown-toggle" type="button" data-toggle="dropdown">+
            <span class="caret"></span></button>
            <ul class="dropdown-menu">
                <li><a href="<?= Url::to(['create-characteristic']) ?>" id="add-charact-btn">Добавить характеристику</a></li>
                <li><a href="<?= Url::to(['load-files']) ?>" id="add-files-btn">Добавить вложение</a></li>
            </ul>
    </div>
    <hr />
    
    <div class="col-md-4">
        <h4>Жилой комплекс</h4>
        <div class="panel-group" id="accordion">
            <?php foreach ($houses_list as $house) : ?>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#house<?= $house['houses_id'] ?>" id="house_link">
                                <?= $house['estate_name'] ?>
                            </a>
                        </h4>
                        <p>
                            <?= FormatHelpers::formatFullAdress($house['estate_town'], $house['houses_street'], $house['houses_number_house']) ?>
                        </p>
                    </div>
                    <div id="house<?= $house['houses_id'] ?>" class="panel-collapse collapse house_accordion <?= $house_cookie == $house['houses_id'] ? 'in' : '' ?>">
                        <div class="panel-body">
                            <?= $house['houses_description'] ? $house['houses_description'] : 'Описание отсутствует' ?>
                            <br />
                            <?= Html::a('Редактировать описание', ['update-description', 'house_id' => $house['houses_id']], [
                                    'class' => 'btn btn-primary btn-sm',
                                    'id' => 'edit-discription-btn',
                            ]) ?>
                            <?= Html::a('<span class="glyphicon glyphicon-edit"></span>', ['view-house', 'house_id' => $house['houses_id']], [
                                    'class' => 'btn btn-link btn-sm',
                            ]) ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    
    <div class="col-md-2">
        <h4>Характеристики</h4>
        <div id="characteristic_list">
            <?= $this->render('data/characteristics_house', ['characteristics' => $characteristics]) ?>
        </div>
    </div>
    
    <div class="col-md-4">
        <h4>Квартиры</h4>
        <div id="flats_list">
            <?= $this->render('data/view_flats', ['flats' => $flats]) ?>
         </div>
    </div>
    
    <div class="col-md-2">
        <h4>Вложения</h4>
        <div id="files_list">
            <?= $this->render('data/view_upload_files', ['files' => $files]) ?>
         </div>
    </div>
    
</div>

<?php
    /* Модальное окно для редактирования описания дома */
    Modal::begin([
        'id' => 'edit-description-house',
        'header' => 'Редактирование',
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
        'clientOptions' => [
            'backdrop' => 'static', 
            'keyboard' => false],
    ]);
?>
<?php Modal::end(); ?>

<?= ModalWindowsManager::widget(['modal_view' => 'estate_house']) ?>
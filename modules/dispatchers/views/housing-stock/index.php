<?php

    use yii\helpers\Html;
    use yii\widgets\Breadcrumbs;
    use app\helpers\FormatHelpers;
    
/*
 * Жилищный фонд, главная страница
 */
$this->title = 'Жилищный фонд';
$this->params['breadcrumbs'][] = 'Жилищный фонд';
$active_item = 0;
?>

<div class="dispatcher-main">
    
    <?= Breadcrumbs::widget([
            'homeLink' => ['label' => 'ELSA | Администратор', 'url' => ['dispatchers/index']],
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
    ]) ?>
    
    <div id="_list-res" class="row housing-stock">
        <div class="col-md-4">
            <h4 class="title">Жилой комплекс (Дома)</h4>
            <div class="panel-group" id="accordion">
                <?php if (isset($houses_list) && is_array($houses_list) && !empty($houses_list)) : ?>
                <?php foreach ($houses_list as $key => $house) : ?>
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
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="col-md-2">
            <h4 class="title">Характеристики</h4>
            <div id="characteristic_list">
                <?= $this->render('data/characteristics_house', ['characteristics' => $houses_list[$active_item]['characteristic']]) ?>
            </div>
        </div>
        
        <div class="col-md-4">
            <div id="flats_list">
                <?= $this->render('data/view_flats', ['flats' => $houses_list[$active_item]['flat']]) ?>
            </div>
        </div>
        
        <div class="col-md-2">
            <h4 class="title">Вложения</h4>
            <div id="files_list">
                <?php // = $this->render('data/view_upload_files', ['files' => $files]) ?>
            </div>
        </div>
    </div>
    
</div>
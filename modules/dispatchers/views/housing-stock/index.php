<?php

    use yii\helpers\Html;
    use yii\widgets\Breadcrumbs;
    use app\helpers\FormatHelpers;
    
/*
 * Жилищный фонд, главная страница
 */
$this->title = Yii::$app->params['site-name-dispatcher'] . 'Жилищный фонд';
$this->params['breadcrumbs'][] = 'Жилищный фонд';

/*
 * Устанавливаем значение для активного дома, выбранного их списка
 * @param array $house_cookie Массив приходит из куки
 */
$active_item = $house_cookie 
        ? ['key' => $house_cookie['key'], 'value' => $house_cookie['value']] 
        : ['key' => 0, 'value' => $houses_list[0]['houses_id']];
?>

<div class="dispatcher-main">
    
    <?= Breadcrumbs::widget([
            'homeLink' => ['label' => 'ELSA | Диспетчер', 'url' => ['dispatchers/index']],
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
    ]) ?>
    
    <div id="_list-res" class="row housing-stock">
        <div class="col-lg-4 col-md-4 col-sm-6 housing-stock__description">
            <h4 class="title">Жилой комплекс (Дома)</h4>
            <div class="panel-group" id="accordion">
                <?php if (isset($houses_list) && is_array($houses_list) && !empty($houses_list)) : ?>
                <?php foreach ($houses_list as $key => $house) : ?>
                    <div class="panel panel-housing-stock">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a data-toggle="collapse" data-parent="#accordion" href="#house<?= $house['houses_id'] ?>" id="house_link" data-key="<?= $key ?>">
                                    <?= $house['houses_name'] ?>
                                </a>
                            </h4>
                            <h4 class="house-adress">
                                <?= "Ул. {$house['houses_street']}, д. {$house['houses_number']}" ?>
                            </h4>
                        </div>
                        <div id="house<?= $house['houses_id'] ?>" class="panel-collapse collapse house_accordion <?= $house_cookie['value'] == $house['houses_id'] ? 'in' : '' ?>">
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
        
        <div class="col-lg-2 col-md-3 col-sm-6 housing-stock__characteristics">
            <h4 class="title">Характеристики</h4>
            <div id="characteristic_list">
                <?= $this->render('data/characteristics_house', [
                        'characteristics' => isset($house_cookie) ? $houses_list[$house_cookie['key']]['characteristic'] : $houses_list[0]['characteristic']]) ?>
            </div>
        </div>
        
        <div class="col-lg-4 col-md-5 col-sm-6 housing-stock__flats">
            <div id="flats_list">
                <?= $this->render('data/view_flats', [
                        'flats' => isset($house_cookie) ? $houses_list[$house_cookie['key']]['flat'] : $houses_list[0]['flat']]) ?>
            </div>
        </div>
        
        <div class="col-lg-2 col-md-12 col-sm-6 housing-stock__files">
            <h4 class="title">Вложения</h4>
            <div id="files_list">
                <?= $this->render('data/view_upload_files', [
                        'files' => isset($house_cookie) ? $houses_list[$house_cookie['key']]['image'] : $houses_list[0]['image']]) ?>
            </div>
        </div>
    </div>
    
</div>
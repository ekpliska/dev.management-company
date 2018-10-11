<?php

    use yii\helpers\Html;
    use yii\bootstrap\Modal;
    use app\modules\managers\widgets\AlertsShow;
    use app\helpers\FormatHelpers;
    use app\modules\managers\widgets\ModalWindowsManager;

/* 
 * Жилищный фонд, главная
 */

$this->title = 'Жилищный фонд';
?>
<div class="managers-default-index">
    <h1><?= $this->title ?></h1>
    <hr />
    
    <?= AlertsShow::widget() ?>
    
    <?= Html::a('Жилищный фонд (+)', ['voting/create'], ['class' => 'btn btn-success btn-sm']) ?>
    <hr />
    
    <div class="col-md-4">
        <div class="panel-group" id="accordion">
            <?php foreach ($houses_list as $house) : ?>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#house<?= $house['houses_id'] ?>">
                                <?= $house['estate_name'] ?>
                            </a>
                        </h4>
                        <p>
                            <?= FormatHelpers::formatFullAdress($house['estate_town'], $house['houses_street'], $house['houses_number_house']) ?>
                        </p>
                    </div>
                    <div id="house<?= $house['houses_id'] ?>" class="panel-collapse collapse in">
                        <div class="panel-body">
                            <?= $house['houses_description'] ? $house['houses_description'] : 'Описание отсутствует' ?>
                            <br />
                            <?= Html::a('Редактировать описание', ['update-description', 'house_id' => $house['houses_id']], [
                                    'class' => 'btn btn-primary btn-sm',
                                    'data-target' => '#edit-description-house',
                                    'data-toggle' => 'modal',
                                    'onclick' => "$('#edit-description-house .modal-dialog .modal-content .modal-body').load($(this).attr('href'))",
                            ]) ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    
    <div class="col-md-2">
        Характеристики дома
    </div>
    
    <div class="col-md-4">
        Квартиры
    </div>
    
    <div class="col-md-2">
        Прикрепленные файлы
    </div>
    
</div>

<?php
    /* Модальное окно для редактирования описания дома */

    Modal::begin([
        'id' => 'edit-description-house',
        'header' => 'Редактирование',
        'clientOptions' => [
            'backdrop' => 'static', 
            'keyboard' => false],
    ]);
?>
<?php Modal::end(); ?>

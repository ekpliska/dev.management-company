<?php

    use yii\widgets\Pjax;
    use yii\helpers\Html;
    use app\models\TypeCounters;

/* 
 * Слайдер для текущих показаний приборов учета
 */
    
$array_image = [
    '1' => 'hold-water',
    '2' => 'hot-water',
    '3' => 'electric-meter',
    '6' => 'electric-meter',
    '4' => 'heating-meter',
    '5' => 'heat-distributor',
];
$current_date = date('Y-m-d');
$item = 0;
$block_send = '<span class="block_send">Ввод показания заблокрован</span><br />' . Html::a('Мои приборы учета', ['counters/index']);
//var_dump($indications); die();
?>

<?php if (!empty($indications) && is_array($indications)) : ?>
<?php Pjax::begin(); ?>
<?= Html::beginForm(['clients/index'], 'post', ['id' => "form-send-indication", 'data-pjax' => '', 'class' => 'form-inline']); ?>
<div class="counters-carousel owl-carousel owl-theme">
    <?php for ($i = 0; $i < (count($indications))/2; $i++) : ?>
    <div class="counters-item">
        <?php for ($j = 0; $j < 2; $j++) : ?>
        <div class="item">
            <div>
                <?php $data_check = (strtotime($current_date) >= strtotime($indications[$item]['Дата следующей поверки'])) ? false : true ?>
                <?= Html::img('/images/counters/' . $array_image[TypeCounters::getTypeID($indications[$item]['Тип прибора учета'])] . '.svg', ['alt' => '', 'class' => 'counters-img']) ?>
                <?= $indications[$item]['Тип прибора учета'] ?>
            </div>
            <div>
                <?php if ($data_check) : ?> 
                    <?= Html::input('text', "counter-{$item}", $indications[$item]['Регистрационный номер прибора учета'], ['class' => 'slider-input']) ?>
                    <?php // = Html::input('text', "indication-{$item}", $indications[$item]['Предыдущие показание'], ['class' => 'slider-input']) ?>
                    <?= Html::input('text', "indication-{$item}", $indications[$item]['Текущее показание'], ['class' => 'slider-input']) ?>
                <?php else: ?>
                    <?= $block_send ?>
                <?php endif; ?>
            </div>
        </div>
        <?php ++$item; ?>
        <?php endfor; ?>
        <div class="item-btn">
            <?= Html::submitButton('Отправить', ['id' => "send-indication-{$i}"]) ?>
        </div>
    </div>
    <?php endfor; ?>
</div>
<?php Html::endForm(); ?>
<?php Pjax::end(); ?>
<?php endif; ?>

<?php
$this->registerJs("
    $('form#form-send-indication').on('submit', function(e) {
        e.preventDefault();
        var valIndication = $('.counters-carousel').find('.active').find(':input');
        
        var dataPost = valIndication.serializeArray();
        $.each(dataPost, function(i, data){
            console.log(data.name);
        });
        
//        valIndication.css('background', 'green');
        
//        console.log(valIndication.serializeArray());
        
        return false;
    });
")
?>
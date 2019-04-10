<?php

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
?>
<?php if (!empty($indications) && is_array($indications)) : ?>
<div class="counters-carousel owl-carousel owl-theme">
    <?php for ($i = 0; $i < (count($indications))/2; $i++) : ?>
    <div class="counters-item">
        <div class="item">
            <div>
                <?php $data_check = (strtotime($current_date) >= strtotime($indications[$item]['Дата следующей поверки'])) ? false : true ?>
                <?= Html::img('/images/counters/' . $array_image[TypeCounters::getTypeID($indications[$item]['Тип прибора учета'])] . '.svg', ['alt' => '', 'class' => 'counters-img']) ?>
                <?= $indications[$item]['Тип прибора учета'] ?>
            </div>
            <div>
                <?= $data_check ? Html::input('text', null, $indications[$item]['Текущее показание'], ['class' => 'slider-input']) : $block_send ?>
            </div>
        </div>
        <?php $item++; ?>
        <div class="item">
            <div>
                <?php $data_check = (strtotime($current_date) >= strtotime($indications[$item]['Дата следующей поверки'])) ? false : true ?>
                <?= Html::img('/images/counters/' . $array_image[TypeCounters::getTypeID($indications[$item]['Тип прибора учета'])] . '.svg', ['alt' => '', 'class' => 'counters-img']) ?>
                <?= $indications[$item]['Тип прибора учета'] ?>
            </div>
            <div>
                <?= $data_check ? Html::input('text', null, $indications[$item]['Текущее показание'], ['class' => 'slider-input']) : $block_send ?>
            </div>
        </div>
        <div class="item-btn">
            <?= Html::button('Отправить', ['id' => "send-indication-{$i}"]) ?>
        </div>
    </div>
    <?php $item++; ?>
    <?php endfor; ?>
</div>
<?php endif; ?>
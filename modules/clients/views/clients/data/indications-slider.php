<?php

    use yii\widgets\Pjax;
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
    use app\models\TypeCounters;

/* 
 * Слайдер для текущих показаний приборов учета
 */
    
// Текущая дата
$current_date = date('Y-m-d');
$item = 0;
$block_send = '<span class="block_send">Ввод показания заблокрован</span><br />' . Html::a('Мои приборы учета', ['counters/index']);
?>

<?php if (!empty($indications) && is_array($indications)) : ?>
<?php Pjax::begin(); ?>
<?php $form = ActiveForm::begin([
        'id' => 'form-send-indication',
        'options' => [
            'data-pjax' => true
        ],
]); ?>
<div class="counters-carousel owl-carousel owl-theme">
    <?php for ($i = 0; $i < (count($indications))/2; $i++) : ?>
    <div class="counters-item">
        <div class="popup-notice">
            <span class="notice-text"></span>
        </div>
        <?php $count_block = 0; // Количество заблокированных приборов учета на 1н слайд ?>
        <?php for ($j = 0; $j < 2; $j++) : ?>
        <div class="item">
            <div>
                <?php $data_check = (strtotime($current_date) >= strtotime($indications[$item]['Дата следующей поверки'])) ? false : true ?>
                <?= Html::img(TypeCounters::getImageCounter($indications[$item]['Тип прибора учета']), ['alt' => '', 'class' => 'counters-img']) ?>
                <?= $indications[$item]['Тип прибора учета'] ?>
            </div>
            <div>
                <?php if ($data_check) : ?> 
                    <?php 
                        // Если текущие показания отсутствуют, то выводим предыдущие
                        $indication_value = !empty($indications[$item]['Текущее показание']) ? $indications[$item]['Текущее показание'] : $indications[$item]['Предыдущие показание'];
                    ?>
                    <?= Html::input('text', "counter-{$item}", $indications[$item]['ID'], ['class' => 'slider-input hidden']) ?>
                    <?= Html::input('text', "indication-{$item}", $indication_value, [                            
                            'class' => 'slider-input',
                            'data-indication' => $indication_value]) ?>
                            
                    <span class="error"></span>
                <?php else: ?>
                    <?php ++$count_block; ?>
                    <?= $block_send ?>
                <?php endif; ?>
            </div>
        </div>
        <?php ++$item; ?>
        <?php endfor; ?>
        <div class="item-btn">
            <?= Html::submitButton('Отправить', ['id' => "send-indication-{$i}", 'disabled' => $count_block >= 2 ? true : false]) ?>
        </div>
    </div>
    <?php endfor; ?>
</div>
<?php ActiveForm::end(); ?>
<?php Pjax::end(); ?>
<?php else: ?>
    <p class="message-general-page">
        Лицевой счет <span><?= $this->context->_current_account_number ?></span> не содержит сведений о приборах учета.
    </p>
<?php endif; ?>

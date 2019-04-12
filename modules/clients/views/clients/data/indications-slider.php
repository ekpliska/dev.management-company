<?php

    use yii\widgets\Pjax;
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
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
<?php $form = ActiveForm::begin([
    'id' => 'form-send-indication',
    'options' => ['data-pjax' => true],
]); ?>
<div class="counters-carousel owl-carousel owl-theme">
    <?php for ($i = 0; $i < (count($indications))/2; $i++) : ?>
    <div class="counters-item">
        <div class="popup-notice">
            <span class="notice-text">A Simple Popup!</span>
        </div>
        <?php for ($j = 0; $j < 2; $j++) : ?>
        <div class="item">
            <div>
                <?php $data_check = (strtotime($current_date) >= strtotime($indications[$item]['Дата следующей поверки'])) ? false : true ?>
                <?= Html::img('/images/counters/' . $array_image[TypeCounters::getTypeID($indications[$item]['Тип прибора учета'])] . '.svg', ['alt' => '', 'class' => 'counters-img']) ?>
                <?= $indications[$item]['Тип прибора учета'] ?>
            </div>
            <div>
                <?php if ($data_check) : ?> 
                    <?= Html::input('text', "counter-{$item}", $indications[$item]['ID'], ['class' => 'slider-input hidden']) ?>
                    <?= Html::input('text', "indication-{$item}", $indications[$item]['Текущее показание'], [                            
                            'class' => 'slider-input',
                            'data-indication' => $indications[$item]['Текущее показание']]) ?>
                            
                    <span class="error"></span>
                <?php else: ?>
                    <?= $block_send ?>
                <?php endif; ?>
            </div>
        </div>
        <?php ++$item; ?>
        <?php endfor; ?>
        <div class="item-btn">
            <?= Html::submitButton('Отправить', ['id' => "send-indication-{$i}", 'disabled' => false]) ?>
        </div>
    </div>
    <?php endfor; ?>
</div>
<?php ActiveForm::end(); ?>
<?php Pjax::end(); ?>
<?php endif; ?>

<?php
$this->registerJs("
    $('form#form-send-indication').on('beforeSubmit.yii', function(e) {
        e.preventDefault();
        var valIndication = $('.counters-carousel').find('.active').find(':input');        
        var dataPost = valIndication.serializeArray();
        var dataForm = {};
        var isCheck = true;
        

        $.each(dataPost, function(index, data) {
            var inputIndication = $('input[name=\"' + data.name + '\"]');
            var prevValue = parseFloat(inputIndication.data('indication'));
            var newValue = parseFloat(data.value);            
            
            // Сравниваем введенное показание с текущим
            if (newValue < prevValue) {
                $(inputIndication).next().text('Ошибка подачи показаний, предыдущее показание ' + prevValue);
                isCheck = false;1
            } else {
                $(inputIndication).next().text('');
            }
            
            // Собираем данные для отправи в AJAX запрос
            if (index%2 == 0) {
                dataForm[dataPost[index].value] = dataPost[index + 1].value;
            }
        });

        console.log(isCheck);
        if (isCheck == false) {
            return false;
        } else {
            $.ajax({
                url: '/clients/clients/send-indications',
                method: 'POST',
                data: {dataForm: dataForm},
                success: function(response) {
                    if (response.success == false) {
                        console.log('Ошибка отправки показаний');
                    } else {
                        console.log('Показания успешно отправлены');
                    }
                },
            });
        }
        return false;
    });
")
?>
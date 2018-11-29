<?php
    
    use kartik\date\DatePicker;
    use yii\helpers\Html;
    
/* 
 * Страница "Квитанции ЖКУ"
 */

$this->title = 'Квитанции ЖКУ'
?>

<div class="receipts-page row">
    <div class="col-md-5 receipts_period">
        <p class="period_title"><i class="glyphicon glyphicon-calendar"></i> Период</p>
        <div class="receipts_period-calendar">
            <span>С</span>
        <?= DatePicker::widget([
                'name' => 'date_start-period',
                'type' => DatePicker::TYPE_INPUT,
                'value' => date('d-M-Y'),
                'pluginOptions' => [
                    'autoclose'=>true,
                    'format' => 'dd-M-yyyy'
                ]
            ]);        
        ?>
            <span>ПО</span>
        <?= DatePicker::widget([
                'name' => 'date_end-period',
                'type' => DatePicker::TYPE_INPUT,
                'value' => date('d-M-Y'),
                'pluginOptions' => [
                    'autoclose'=>true,
                    'format' => 'dd-M-yyyy'
                ]
            ]);        
        ?>
        </div>
        <ul class="list-group receipte-of-lists">
            <li class="list-group-item">
                <p class="receipte-month">Месяц</p>
                <p class="receipte-number">Квитанция #TODO</p>
                <?= Html::a('К оплате: #TODO &#8381;', ['/'], ['class' => 'receipte-btn-pay']) ?>
                <?= Html::a('<i class="glyphicon glyphicon-download-alt"></i>', ['/'], ['class' => 'receipte-btn-dowload']) ?>
            </li>
            <li class="list-group-item">
                <p class="receipte-month">Месяц</p>
                <p class="receipte-number">Квитанция #TODO</p>
                <?= Html::a('К оплате: #TODO &#8381;', ['/'], ['class' => 'recipte-btn-pay']) ?>
                <?= Html::a('<i class="glyphicon glyphicon-download-alt"></i>', ['/'], ['class' => 'receipte-btn-dowload']) ?>
            </li>
        </ul>
    </div>
    <div class="col-md-7 receipts_body">
        #TODO
    </div>
    
    <div class="operations-block">
        <ul class="nav nav-pills operations-block_items">
            <li><a href="#" class="left-block"><i class="glyphicon glyphicon-print"></i> Распечатать</a></li>
            <li><a href="#" class="center-block"><i class="glyphicon glyphicon-ruble"></i> Оплатить</a></li>
            <li><a href="#" class="right-block"><i class="glyphicon glyphicon-send"></i> Отправить</a></li>
        </ul>
    </div>
</div>
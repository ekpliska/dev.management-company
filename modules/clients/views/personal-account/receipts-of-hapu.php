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
        <ul class="list-group recipte-of-lists">
            <li class="list-group-item">
                <p class="recipte-month">Месяц</p>
                <p class="recipte-number">Квитанция #TODO</p>
                <?= Html::a('К оплате: #TODO &#8381;', ['/'], ['class' => 'recipte-btn-pay']) ?>
                <?= Html::a('<i class="glyphicon glyphicon-download-alt"></i>', ['/'], ['class' => 'recipte-btn-dowload']) ?>
            </li>
            <li class="list-group-item">
                <p class="recipte-month">Месяц</p>
                <p class="recipte-number">Квитанция #TODO</p>
                <?= Html::a('К оплате: #TODO &#8381;', ['/'], ['class' => 'recipte-btn-pay']) ?>
                <?= Html::a('<i class="glyphicon glyphicon-download-alt"></i>', ['/'], ['class' => 'recipte-btn-dowload']) ?>
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
<!--<div class="big-conteiner">
    <div class="payment-ter">
        <p class="date-recept"><img src="assets/img/icons8.svg" width="11.8px" height="11.8px"> Период</p>
        <div class="period">
            <ul class="nav nav-pills mx-auto justify-content-start">
                <li class="nav-item">
                    C<label for="date-start" class=""><input class=" mx-2 date-prop form-control" type="text" placeholder="" id="date-start"></label>По<label for="date-end" class="mx-2"><input class="date-prop form-control mx-2 " type="text" placeholder="" id="date-end"></label>
                </li>
            </ul>
        </div>
        <div class="receipt-paid">
            <p class="september">Сентябрь 2018</p><p class="ticket">Квитанция 3483</p>
            <button type="button" class="battom-payment">1546 Р</button>
            <label class="text-center btn btn-upload" role="button"><img src="assets/img/download.svg" class="upload-icon" height="15px" width="15px"><input type="file" hidden></label>
        </div>
        <div class="receipt-paid"><p class="september">Сентябрь 2018</p><p>Квитанция 3483</p>
            <button type="button" class="battom-payment-ok"><img class src="assets/img/Shape.svg">Оплачено 4841 Р</button>
            <label class="text-center btn btn-upload" role="button"><img src="assets/img/download.svg" class="upload-icon" height="15px" width="15px"><input type="file" hidden></label>
      </div>
        <div class="receipt-paid"><p class="september">Сентябрь 2018</p><p>Квитанция 3483</p>
            <button type="button" class="battom-payment-ok"><img src="assets/img/Shape.svg">Оплачено  3480 Р</button>
            <label class="text-center btn btn-upload" role="button"><img src="assets/img/download.svg" class="upload-icon" height="15px" width="15px"><input type="file" hidden></label>
        </div>
        <div class="receipt-paid"><p class="september">Сентябрь 2018</p><p>Квитанция 3483</p>
            <button type="button" class="battom-payment-ok"><img src="assets/img/Shape.svg">Оплачено 2400 Р</button>
            <label class="text-center btn btn-upload" role="button"><img src="assets/img/download.svg" class="upload-icon" height="15px" width="15px"><input type="file" hidden></label>
        </div>
        <div class="receipt-paid"><p class="september">Сентябрь 2018</p><p>Квитанция 3483</p>
            <button type="button" class="battom-payment-ok"><img src="assets/img/Shape.svg">Оплачено 3256 Р</button>
            <label class="text-center btn btn-upload" role="button"><img src="assets/img/download.svg" class="upload-icon" height="15px" width="15px"><input type="file" hidden></label>
        </div>
        <div class="receipt-paid"><p class="september">Сентябрь 2018</p><p>Квитанция 3483</p>
            <button type="button" class="battom-payment-ok"><img src="assets/img/Shape.svg">Оплачено 3256 Р</button>
            <label class="text-center btn btn-upload" role="button"><img src="assets/img/download.svg" class="upload-icon" height="15px" width="15px"><input type="file" hidden></label>
        </div>
    </div>
    <div class="button-receip">
        <div class="btn-group receipt-hot receipt-but" role="group" aria-label="Basic example">
            <button type="button" class="btn btn-secondary1"><img src="/assets/img/print.svg" height="25px" width="25px"> Распечатать</button>
            <button type="button" class="btn btn-secondary2"><img src="/assets/img/credit-card.svg" height="25px" width="25px"> Оплатить</button>
            <button type="button" class="btn btn-secondary3"><img src="/assets/img/paper-plane.svg" height="25px" width="25px"> Отправить</button>
        </div>
    </div>
</div>-->
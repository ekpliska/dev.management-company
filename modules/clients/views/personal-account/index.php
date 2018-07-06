<?php
    use yii\widgets\DetailView;
    use yii\helpers\Html;
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$this->title = 'Лицевой счет | Общая информация';
?>
<div class="clients-default-index">
    <h1><?= $this->title ?></h1>
    <div class="col-md-6">
        Лицевой счет
        <?php
            var_dump($number_account)
        ?>
    </div>
    <div class="col-md-6">
        Добавить лицевой счет
    </div>
    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="text-left">
                    <strong>Лицевой счет</strong>                    
                </div>
            </div>            
            <div class="panel-body">
                <?= DetailView::widget([
                    'model' => $account,
                    'attributes' => [
                        'account_number',
                        'account_organization',
                        [
                            'attribute' => 'Собственник',
                            'value' => $account->client->fullName,
                        ],
                        [
                            'attribute' => 'Телефон',
                            'value' => $account->client->phone,
                        ],
                        $account->rent->fullName ? 
                        [
                            'attribute' => 'Арендатор',
                            'value' => $account->rent->fullName,
                        ] : [],
                        [
                            'attribute' => 'Адрес',
                            'value' => $account->house->adress,                            
                        ],
                        [
                            'attribute' => 'Парадная',
                            'value' => $account->house->porch,                            
                        ],
                        [
                            'attribute' => 'Этаж',
                            'value' => $account->house->floor,                            
                        ],
                        [
                            'attribute' => 'Количество комнат',
                            'value' => $account->house->rooms,                            
                        ],
                        [
                            'attribute' => 'Жилая площадь (кв.м.)',
                            'value' => $account->house->square,                            
                        ],                        
                    ],
                ]) ?>                
            </div>
        </div>
    </div>    
    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="text-left">
                    <strong>Информаци об оплате</strong>                    
                </div>
            </div>            
            <div class="panel-body">
                test
            </div>
        </div>
    </div>    
</div>
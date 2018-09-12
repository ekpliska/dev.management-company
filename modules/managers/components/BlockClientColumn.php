<?php

    namespace app\modules\managers\components;
    use yii\grid\DataColumn;
    use yii\helpers\Html;
    use app\models\User;

/**
 * Формирование колонки "Блокировать/Разблокировать" в таблице Клиенты
 */
class BlockClientColumn extends DataColumn {
    
    // Название поля  БД
    public $attribute = 'status';
    // Действие контроллера
    public $action = 'block-client';
    
    public $client_key = 'clientId';
    public $status_key = 'statusClient';
    public $ajax_method = 'POST';

    public function init() {
        
        $this->grid->view->registerJs("
            $('body').on('click', 'button[type=button]', function(e) {
                e.preventDefault();
                var clientId = $(this).data('clientId');
                var statusClient = $(this).data('status');
                var btnValue = $('[data-client-id=' + clientId + '] .glyphicon');
                
                console.log(statusClient);
                //console.log(btnValue);
                //console.log(clientId + ' ' + statusClient);

                $.ajax({
                    url: '" . $this->action . "',
                    type: '" . $this->ajax_method . "',
                    data: {
                        {$this->client_key} : clientId,
                        {$this->status_key} : statusClient,
                    },
                    success: function(response) {
                        console.log('ajax ' + response.status);
                        if (response.status == " . User::STATUS_BLOCK . ") {
                            console.log('block to unblok');
                            btnValue.removeClass('glyphicon-lock');
                            btnValue.addClass('glyphicon-ok-circle');
                        } else {
                            if (response.status == " . User::STATUS_ENABLED . ") {
                                console.log('unblok to block');
                                btnValue.removeClass('glyphicon-ok-circle');
                                btnValue.addClass('glyphicon-lock');
                            }
                        }
                    },
                    error: function() {
                        console.log('error');
                    },
                });
                
                // return false;
                
            });
        ");
        
    }

    protected function renderDataCellContent($data) {
        if ($data['status'] == 1) {
            return 
                Html::button('<span class="glyphicon glyphicon-ok-circle"></span>', 
                        [
                            'class' => 'form-control status_btn',
                            'data' => [
                                'client-id' => $data['client_id'],
                                'status' => User::STATUS_ENABLED,
                            ],
                        ]);
        } elseif ($data['status'] == 2) {
            return 
                Html::button('<span class="glyphicon glyphicon-lock"></span>', 
                        [
                            'class' => 'form-control status_btn',
                            'data' => [
                                'client-id' => $data['client_id'],
                                'status' => User::STATUS_BLOCK,
                            ],
                        ]);          
        }
    }
    
    
}

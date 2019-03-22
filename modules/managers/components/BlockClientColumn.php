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
    // Заголовок колонки
    public $header = 'Статус';
    // Действие контроллера для блокировки собсвенника
    public $block_action = 'clients/block-client';
    // Действие контроллера для удаления собсвенника
    public $delete_action = 'clients/delete-client';
    // Пост параметр
    public $client_key = 'clientId';
    // Пост параметр
    public $status_key = 'statusClient';
    // Метод передачи ajax
    public $ajax_method = 'POST';

    /*
     * Формируем ajax запрос на каждый элемент чекбокса в колонке таблицы
     */
    public function init() {
        
        $this->grid->view->registerJs("
        
            var clientId;

            $('body').on('click', '#block_client', function(e) {
                e.preventDefault();
                // Получаем data атрибут ID собственика
                clientId = $(this).data('clientId');
                // Получаем data атрибут статус собственика
                var statusClient = $(this).data('status');
                // Собираем все элементы, которые содержат одинаковые data атрибут ID собственика
                var btnValue = $('.user-' + clientId);

                $.ajax({
                    url: '" . $this->block_action . "',
                    type: '" . $this->ajax_method . "',
                    data: {
                        {$this->client_key} : clientId,
                        {$this->status_key} : statusClient,
                    },
                    success: function(response) {
                        if (response.status == " . User::STATUS_BLOCK . ") {
                            btnValue.addClass('_status-user_block');
                            btnValue.data('status', 1);
                        } else {
                            if (response.status == " . User::STATUS_ENABLED . ") {
                                btnValue.removeClass('_status-user_block');
                                btnValue.addClass('_status-user');
                                btnValue.data('status', 2);
                            }
                        }
                    },
                    error: function() {
                        console.log('#2000 - 01: Ошибка Ajax запроса');
                    },
                });
                
                return false;
            });
            
        ");
        
    }

    /*
     *  Формируем колонку checkbox Блокировать/Разблокировать учетную запись Собственника 
     * 
     * @param integer $data['status'] == User::STATUS_ENABLED (1) Собственник активен
     * @param integer $data['status'] == User::STATUS_BLOCK (2) Собственник заблокирован
     */    
    protected function renderDataCellContent($data) {
        
        $data_array = [];
        $classBtn = '';
        $message = '';
        
        if ($data['status'] == User::STATUS_ENABLED) {
            $data_array = [
                'client-id' => $data['client_id'],
                'status' => USER::STATUS_BLOCK,
            ];
            $classBtn = "_status-user user-{$data['client_id']}";
            $message = 'Пользователь разблокирован';
        } elseif ($data['status'] == User::STATUS_BLOCK) {
            $data_array = [
                'client-id' => $data['client_id'],
                'status' => User::STATUS_ENABLED,
            ];            
            $classBtn = "_status-user_block user-{$data['client_id']}";
            $message = 'Пользователь заблокирован';
        }
        
        return
            Html::button('', ['class' => $classBtn, 'id' => 'block_client', 'data' => $data_array, 'title' => $message]);
                        
    }
    
    
}

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
    public $block_action = 'block-client';
    // Действие контроллера для удаления собсвенника
    public $delete_action = 'delete-client';
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

            $('body').on('click', 'ul.dropdown-setting li#block_client', function(e) {
                e.preventDefault();
                // Получаем data атрибут ID собственика
                clientId = $(this).data('clientId');
                // Получаем data атрибут статус собственика
                var statusClient = $(this).data('status');
                // Собираем все элементы, которые содержат одинаковые data атрибут ID собственика
                var btnValue = $(this);

                $.ajax({
                    url: '" . $this->block_action . "',
                    type: '" . $this->ajax_method . "',
                    data: {
                        {$this->client_key} : clientId,
                        {$this->status_key} : statusClient,
                    },
                    success: function(response) {
                        if (response.status == " . User::STATUS_BLOCK . ") {
                            btnValue.html('&Oslash;&nbsp;&nbsp;Разблокировать');
                            btnValue.data('status', 1);
                        } else {
                            if (response.status == " . User::STATUS_ENABLED . ") {
                                btnValue.html('&Oslash;&nbsp;&nbsp;Заблокировать');
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
            
            $('body').on('click', 'ul.dropdown-setting li#delete_client', function(e) {
                e.preventDefault();
                // Получаем data атрибут ID собственика
                clientId = $(this).data('clientId');
                $('#delete_clients_manager').modal('show');
            });
            
            $('body').on('click', '.delete_client__del', function(){
                $.ajax({
                    url: '" . $this->delete_action . "',
                    type: '" . $this->ajax_method . "',
                    data: {
                        {$this->client_key} : clientId,
                    },
                    success: function(response) {
                        // console.log(response);
                    },
                    error: function() {
                        console.log('#2000 - 01: Ошибка Ajax запроса');
                    },
                });
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
        
        if ($data['status'] == User::STATUS_ENABLED) {
            $data_array = [
                'client-id' => $data['client_id'],
                'status' => USER::STATUS_BLOCK,
            ];
            $label = 'Заблокировать';
        } elseif ($data['status'] == User::STATUS_BLOCK) {
            $data_array = [
                'client-id' => $data['client_id'],
                'status' => User::STATUS_ENABLED,
            ];            
            $label = 'Разблокировать';
        }
        
        return
            Html::beginTag('div', ['class' => 'dropdown']) .
            Html::button('<i class="glyphicon glyphicon-option-horizontal"></i>', ['class' => 'btn-settings dropdown-toggle', 'type' => 'button', 'data-toggle' => 'dropdown']) .
                Html::beginTag('ul', ['class' => 'dropdown-menu dropdown-setting']) . 
                    Html::beginTag('li', ['id' => 'block_client', 'data' => $data_array]) .
                        "&Oslash;&nbsp;&nbsp;{$label}" .
                    Html::endTag('li') .
                    Html::beginTag('li', ['id' => 'delete_client', 'data' => $data_array]) .
                        '&times;&nbsp;&nbsp;Удалить собсвенника' .
                    Html::endTag('li') .
                Html::endTag('ul') . 
            Html::endTag('div');
                        
    }
    
    
}

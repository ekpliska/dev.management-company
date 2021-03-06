<?php

    use yii\grid\GridView;
    use yii\helpers\Html;
    use app\helpers\FormatHelpers;
    use app\helpers\StatusHelpers;
    use app\helpers\FormatFullNameUser;

/*
 * Вывод таблицы заявки пользователя
 */
?>
<div class="grid-view table-container">
    <?= GridView::widget([
        'dataProvider' => $paid_requests,
        'layout' => '{items}{pager}',
        'tableOptions' => [
            'class' => 'table managers-table',
        ],
        'columns' => [
//            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'request_number',
                'header' => 'ID',
                'value' => function ($data) {
                    return Html::a($data['number'], ['paid-requests/view-paid-request', 'request_number' => $data['number']]);
                },
                'format' => 'raw',
                'contentOptions' =>[
                    'class' => 'managers-table_small',
                ],
            ],
            [
                'attribute' => 'category',
                'header' => 'Вид услуги <br /> Наименование услуги',
                'value' => function($data) {
                    return $data['category'] . '<br />' . $data['service_name'];
                },
                'format' => 'raw',
                'contentOptions' =>[
                    'class' => 'managers-table_middle',
                ],
            ],
            [
                'attribute' => 'date_create',
                'header' => 'Дата постановки',
                'value' => function ($data) {
                    return FormatHelpers::formatDate($data['date_create']);
                },
            ],
            [
                'attribute' => 'client_name',
                'header' => 'Клиент',
                'value' => function ($data) {
                    return $data['clients_surname'] . ' ' . $data['clients_second_name'] . ' ' . $data['clients_name'];
                },
                'contentOptions' => [
                    'class' => 'managers-table_middle',
                ],
                'format' => 'raw',
            ],
            [
                'attribute' => 'name_d',
                'header' => 'Диспетчер',
                'value' => function ($data) {
                    return 
                        FormatFullNameUser::fullNameEmployee($data['employee_id_d'], true, false, [$data['surname_d'], $data['name_d'], $data['sname_d']]); die();
                },
                'contentOptions' => [
                    'class' => 'managers-table_middle',
                ],
                'format' => 'raw',
            ],
            [
                'attribute' => 'name_s',
                'header' => 'Специалист',
                'value' => function ($data) {
                    return 
                        FormatFullNameUser::fullNameEmployee($data['employee_id_s'], false, false, [$data['surname_s'], $data['name_s'], $data['sname_s']]);
                },
                'contentOptions' => [
                    'class' => 'managers-table_middle',
                ],
                'format' => 'raw',
            ],
            [
                'attribute' => 'status',
                'header' => 'Статус',
                'value' => function ($data) {
                    return StatusHelpers::requestStatus($data['status'], $data['id'], false, null);
                },
                'format' => 'raw',
                'contentOptions' => [
                    'class' => 'managers-table_small',
                ],
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'visible' => Yii::$app->user->can('PaidRequestsEdit') ? true : false,
                'template' => '{view-paid-request} {delete-request}',
                'buttons' => [
                    'delete-request' => function ($url, $data) {
                        return 
                            Html::button('<i class="fa fa-trash-o" aria-hidden="true"></i>', [
                                'data-pjax' => false,
                                'class' => 'btn-delete-record__table',
                                'data-target' => '#delete-request-message',
                                'data-toggle' => 'modal',
                                'data-request-type' => 'paid-requests',
                                'data-request' => $data['id'],
                            ]);
                    },
                ],
            ],
        ],
    ]); ?>
</div>
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
<div class="grid-view">
    <?= GridView::widget([
        'dataProvider' => $requests,
        'layout' => '{items}{pager}',
        'tableOptions' => [
            'class' => 'table managers-table',
        ],
        'columns' => [
//            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'number',
                'header' => 'ID',
                'value' => function ($data) {
                    return Html::a($data['number'], ['requests/view-request', 'request_number' => $data['number']]);
                },
                'format' => 'raw',
                'contentOptions' =>[
                    'class' => 'managers-table_small',
                ],
            ],
            [
                'attribute' => 'date_create',
                'header' => 'Дата постановки',
                'value' => function ($date){
                    return FormatHelpers::formatDate($date['date_create']);
                },
            ],
            [
                'attribute' => 'Клиент',
                'header' => 'Клиент',
                'value' => function ($data) {
                    return $data['clients_surname'] . ' ' . $data['clients_second_name'] . ' ' . $data['clients_name'];
                },
                'contentOptions' => [
                    'class' => 'managers-table_middle managers-table_left',
                ],
            ],
            [
                'attribute' => 'name_d',
                'header' => 'Диспетчер',
                'contentOptions' => [
                    'class' => 'managers-table_middle',
                ],
                'value' => function ($data) {
                    return FormatFullNameUser::fullNameEmployer($data['employee_id_d'], true, false);
                },
                'format' => 'raw',
            ],
            [
                'attribute' => 'name_s',
                'header' => 'Специалист',
                'contentOptions' => [
                    'class' => 'managers-table_middle',
                ],
                'value' => function ($data) {
                    return FormatFullNameUser::fullNameEmployer($data['employee_id_s'], true, false);
                },
                'format' => 'raw',
            ],
            [
                'attribute' => 'status',
                'header' => 'Статус',
                'value' => function ($data) {
                    return StatusHelpers::requestStatus($data['status'], $data->requests_id, false);
                },
                'format' => 'raw',
                'contentOptions' => [
                    'class' => 'managers-table_middle',
                ],
            ],
            [
                'attribute' => 'status',
                'header' => 'Рейтинг',
                'value' => function ($data) {
                    return '#TODO';
                },
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{delete-request}',
                'buttons' => [
                    'delete-request' => function ($url, $data) {
                        return 
                            Html::button('<i class="glyphicon glyphicon-trash"></i>', [
                                'data-pjax' => false,
                                'class' => 'btn btn-delete-record__table',
                                'data-target' => '#',
                                'data-toggle' => 'modal',
                            ]);
                    },
                ],
            ],
        ],
    ]); ?>
</div>
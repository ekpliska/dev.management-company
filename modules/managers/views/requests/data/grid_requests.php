<?php

    use yii\grid\GridView;
    use yii\helpers\Html;
    use app\helpers\FormatHelpers;
    use app\helpers\StatusHelpers;
    use app\helpers\FormatFullNameUser;
    use app\modules\clients\widgets\RatingRequest;

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
                'value' => function ($data){
                    return FormatHelpers::formatDate($data['date_create']);
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
                    return
                        FormatFullNameUser::fullNameEmployee($data['employee_id_d'], true, false, [$data['surname_d'], $data['name_d'], $data['sname_d']]);
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
                    return
                        FormatFullNameUser::fullNameEmployee($data['employee_id_s'], false, false, [$data['surname_s'], $data['name_s'], $data['sname_s']]);
                },
                'format' => 'raw',
            ],
            [
                'attribute' => 'status',
                'header' => 'Статус',
                'value' => function ($data) {
                    return StatusHelpers::requestStatus($data['status'], $data->requests_id, false, $data['requests_grade']);
                },
                'format' => 'raw',
                'contentOptions' => [
                    'class' => 'managers-table_small',
                ],
            ],
            [
                'attribute' => 'status',
                'header' => 'Рейтинг',
                'value' => function ($data) {
                    return RatingRequest::widget([
                        '_status' => $data['status'], 
                        '_request_id' => $data['number'],
                        '_score' => $data['grade']]);
                },
                'format' => 'raw',
                'contentOptions' =>[
                    'class' => 'managers-table_middle',
                ],
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{delete-request}',
                'buttons' => [
                    'delete-request' => function ($url, $data) {
                        return 
                            Html::button('<i class="glyphicon glyphicon-trash"></i>', [
                                'data-pjax' => false,
                                'class' => 'btn-delete-record__table',
                                'data-target' => '#delete-request-message',
                                'data-toggle' => 'modal',
                                'data-request-type' => 'requests',
                                'data-request' => $data['requests_id'],
                            ]);
                    },
                ],
            ],
        ],
    ]); ?>
</div>
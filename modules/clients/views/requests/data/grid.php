<?php
    use yii\grid\GridView;
    use yii\helpers\Url;
    use yii\helpers\Html;
    use app\helpers\FormatHelpers;
    use app\helpers\StatusHelpers;
    use app\helpers\FormatFullNameUser;
    use app\modules\clients\widgets\RatingRequest;

/*
 * Вывод таблицы заявок текущего пользователя
 */
?>
    <?= GridView::widget([
        'dataProvider' => $all_requests,
        'layout' => '{items}{pager}',
        'tableOptions' => [
            'class' => 'table clients-table',
        ],
        'pager' => [
            'prevPageLabel' => '<i class="glyphicon glyphicon-menu-left"></i>',
            'nextPageLabel' => '<i class="glyphicon glyphicon-menu-right"></i>',
        ],
        'columns' => [
            [
                'attribute' => 'ID',
                'value' => function ($data) {
                    return Html::a($data->requests_ident, ['requests/view-request', 'request_number' => $data->requests_ident]);
                },
                'contentOptions' =>[
                    'class' => 'clients-table_main',
                ],
                'format' => 'raw',
            ],
            [
                'attribute' => 'Вид заявки',
                'value' => function ($data) {
                    return $data->getNameRequest() . 
                            RatingRequest::widget([
                                '_status' => $data->status, 
                                '_request_id' => $data->requests_ident,
                                '_score' => $data->requests_grade]);
                },
                'format' => 'raw',
                'contentOptions' =>[
                    'class' => 'clients-table_main',
                ],        
            ],
            [
                'attribute' => 'Описание',
                'value' => function ($data) {
                    return $data->requests_comment
                            . '<br />'
                            . FormatHelpers::imageRequestList($data['image']);
                },
                'contentOptions' =>[
                    'class' => 'clients-table_description',
                ],
                'format' => 'raw',
            ],                        
            [
                'attribute' => 'Исполнитель',
                'value' => function ($data) {
                    return FormatFullNameUser::nameEmployee(
                            $data->employeeSpecialist->employee_surname, 
                            $data->employeeSpecialist->employee_name, 
                            $data->employeeSpecialist->employee_second_name, 
                            false);
                },
                'format' => 'raw',
            ],
            [
                'attribute' => 'Дата создания',
                'value' => function ($data) {
                    return FormatHelpers::formatDate($data->created_at, false, 0, false);
                },
                'contentOptions' =>[
                    'class' => 'clients-table_main',
                ],
            ],
            [
                'attribute' => 'Дата закрытия',
                'value' => function ($data) {
                    return FormatHelpers::formatDate($data->date_closed, false, 0, false);
                },
                'contentOptions' =>[
                    'class' => 'clients-table_main',
                ],
            ],
            [
                'attribute' => 'Статус',
                'label' => 'Статус',
                'value' => function ($data) {
                    return StatusHelpers::requestStatus($data['status'], $data->requests_id, true, $data->requests_grade);
                },
                'format' => 'raw',
                'contentOptions' =>[
                    'class' => 'clients-table_main',
                ],                        
            ],
//            [
//                'class' => 'yii\grid\ActionColumn',
//            ],
        ],
    ]); ?>
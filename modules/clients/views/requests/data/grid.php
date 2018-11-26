<?php
    use yii\grid\GridView;
    use yii\helpers\Url;
    use yii\helpers\Html;
    use app\helpers\FormatHelpers;
    use app\helpers\StatusHelpers;

/*
 * Вывод таблицы заявок текущего пользователя
 */
?>
    <?= GridView::widget([
        'dataProvider' => $all_requests,
        'layout' => '{items}{pager}',
        'tableOptions' => [
            'class' => 'table requests-table',
        ],
        'columns' => [
            [
                'attribute' => 'ID',
                'value' => function ($data) {
                    return Html::a($data->requests_ident, ['requests/view-request', 'request_numder' => $data->requests_ident]);
                },
                'headerOptions' => [
                    'style' => 'width: 10%',
                ],
                'contentOptions' =>[
                    'class' => 'req-table_req-main',
                ],
                'format' => 'raw',
            ],
            [
                'attribute' => 'Вид заявки',
                'value' => function ($data) {
                    return $data->getNameRequest();            
                },
                'headerOptions' => [
                    'style' => 'width: 20%',
                ],
                'contentOptions' =>[
                    'class' => 'req-table_req-type',
                ],        
            ],
            [
                'attribute' => 'Описание',
                'value' => function ($data) {
                    return $data->requests_comment
                            . '<br />'
                            . FormatHelpers::imageRequestList($data['image']);
                },
                'headerOptions' => [
                    'style' => 'width: 30%',
                ],                        
                'contentOptions' =>[
                    'class' => 'req-table-description-request',
                ],
                'format' => 'raw',
            ],                        
            [
                'attribute' => 'Исполнитель',
                'value' => function ($data) {
                    return $data->requests_specialist_id;
                },
                'headerOptions' => [
                    'style' => 'width: 10%'
                ],                                
            ],
            [
                'attribute' => 'Дата создания',
                'value' => function ($data) {
                    return FormatHelpers::formatDate($data->created_at, false, 0, false);
                },
                'headerOptions' => [
                    'style' => 'width: 10%'
                ],                
                'contentOptions' =>[
                    'class' => 'req-table_req-main',
                ],
            ],
            [
                'attribute' => 'Дата закрытия',
                'value' => function ($data) {
                    return FormatHelpers::formatDate($data->date_closed, false, 0, false);
                },
                'headerOptions' => [
                    'style' => 'width: 10%'
                ],                
                'contentOptions' =>[
                    'class' => 'req-table_req-main',
                ],
            ],
            [
                'attribute' => 'Статус',
                'label' => 'Статус',
                'value' => function ($data) {
                    return StatusHelpers::requestStatus($data['status'], $data->requests_id);
                },
                'format' => 'raw',
                'headerOptions' => [
                    'style' => 'width: 10%'
                ],                        
                'contentOptions' =>[
                    'class' => 'req-table_req-main',
                ],                        
            ],
//            [
//                'class' => 'yii\grid\ActionColumn',
//            ],
        ],
    ]); ?>
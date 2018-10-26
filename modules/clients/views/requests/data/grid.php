<?php
    use yii\grid\GridView;
    use yii\helpers\Url;
    use yii\helpers\Html;
    use app\helpers\FormatHelpers;

/*
 * Вывод таблицы заявок текущего пользователя
 */
?>
    <?= GridView::widget([
        'dataProvider' => $all_requests,
//        'filterUrl' => Url::to(['requests/index']),
        'layout' => '{items}{pager}',
        'tableOptions' => [
            'class' => 'table req-table  pay-table account-info-table px-0',
        ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'requests_ident',
                'value' => function ($data) {
                    return Html::a($data->requests_ident, ['requests/view-request', 'request_numder' => $data->requests_ident]);
                },
                'contentOptions' =>[
                    'class' => 'req-table-description',
                ],
                'format' => 'raw',
            ],
            [
                'attribute' => 'requests_type_id',
                'value' => function ($data) {
                    return $data->getNameRequest();            
                },
                'contentOptions' =>[
                    'class' => 'req-table-description',
                ],        
            ],
            [
                'attribute' => 'requests_comment',
                'value' => function ($data) {
                    return $data->requests_comment
                            . '<br />'
                            . FormatHelpers::imageRequestList($data['image']);
                },
                'contentOptions' =>[
                    'class' => 'req-table-description-request',
                ],
                'format' => 'raw',
            ],                        
            'requests_specialist_id',
            [
                'attribute' => 'created_at',
                'format' => ['date', 'php:d.m.Y'],
                'contentOptions' =>[
                    'class' => 'date-req-table',
                ],
            ],
            [
                'attribute' => 'updated_at',
                'format' => ['date', 'php:d.m.Y'],
                'contentOptions' =>[
                    'class' => 'date-req-table',
                ],
            ],
            [
                'attribute' => 'status',
                'label' => 'Статус',
                'value' => function ($data) {
                    return FormatHelpers::statusName($data['status']);
                },
                'format' => 'raw',
            ],
            [
                'class' => 'yii\grid\ActionColumn',
//                'template' => '{view-request}',
//                'buttons' => [
//                    'view-request' => function ($url, $data) {
//                        $url = ['request_numder' => $data->requests_ident];
//                        return '<a href='. Url::to(['requests/view-request', 'request_numder' => $data->requests_ident]) .' data-pjax="0" class="btn btn-info">Подробнее</a>';
//                    },
//                ],
            ],
        ],
    ]); ?>
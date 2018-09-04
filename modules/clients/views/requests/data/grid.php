<?php
    use yii\grid\GridView;
    use yii\helpers\Url;
    use app\helpers\FormatHelpers;

/*
 * Вывод таблицы заявок текущего пользователя
 */
?>
    <?= GridView::widget([
        'dataProvider' => $all_requests,
        'filterUrl' => Url::to(['requests/index']),
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'requests_ident',
            [
                'attribute' => 'requests_type_id',
                'value' => function ($data) {
                    return $data->getNameRequest();            
                },
            ],
            'requests_specialist_id',
            [
                'attribute' => 'created_at',
                'format' => ['date', 'php:d.m.Y'],
            ],
            [
                'attribute' => 'requests_comment',
                'value' => function ($data) {
                    return
                        '<div class="cutstring" data-display="none" data-max-length="70">'
                            . $data->requests_comment
                        . '</div>';
                },
                'format' => 'raw',
            ],
            [
                'attribute' => 'updated_at',
                'format' => ['date', 'php:d.m.Y'],
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
                'controller' => 'requests/index',
                'template' => '{view-request}',
                'buttons' => [
                    'view-request' => function ($url, $data) {
                        $url = ['request_numder' => $data->requests_ident];
                        return '<a href='. Url::to(['requests/view-request', 'request_numder' => $data->requests_ident]) .' data-pjax="0" class="btn btn-info">Подробнее</a>';
                    },
                ],
            ],
        ],
    ]); ?>
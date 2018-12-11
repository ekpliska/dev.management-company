<?php
    use yii\grid\GridView;
    use yii\helpers\Html;
    use yii\helpers\Url;
    use app\helpers\FormatHelpers;
    use app\modules\managers\components\BlockClientColumn;

/*
 * Вывод таблицы зарегистрированных собственников
 */
?>
    <?= GridView::widget([
        'dataProvider' => $client_list,
        'layout' => '{items}{pager}',
        'tableOptions' => [
            'class' => 'table managers-table',
        ],
        'columns' => [
            [
                'header' => 'ID',
                'value' => function ($data) {
                    return $data['client_id'];
                },
                'contentOptions' =>[
                    'class' => 'managers-table_small',
                ],
            ],
            [
                'header' => 'ФИО',
                'value' => function ($data) {
                    $url = ['clients/view-client', 'client_id' => $data['client_id'], 'account_number' => $data['number']];
                    return $data['surname'] . ' ' .
                            $data['name'] .  ' ' .
                            $data['second_name'] . "\n" . 
                            Html::a('Профиль', $url);
                },
                'contentOptions' => [
                    'class' => 'managers-table_middle managers-table_left',
                ],
                'format' => 'raw',
            ],
            [
                'attribute' => 'adress',
                'header' => 'Адрес',
                'value' => function ($data) {
                    return '<i class="glyphicon glyphicon-map-marker"></i> ' .
                        FormatHelpers::formatFullAdress(
                            $data['town'], 
                            $data['street'], 
                            $data['house'], 
                            false, 
                            false, 
                            $data['flat']);
                },
                'format' => 'raw',
                'contentOptions' => [
                    'class' => 'managers-table_left',
                ],
            ],
            [
                'attribute' => 'number',
                'header' => 'Лицевой счет',
                'contentOptions' => [
                    'class' => 'managers-table_middle',
                ],
            ],
            [
                'attribute' => 'balance',
                'header' => 'Баланс',
                'value' => function ($data) {
                    return FormatHelpers::formatBalance($data['balance']);
                },
                'format' => 'raw',
                'contentOptions' => [
                    'class' => 'managers-table_small',
                ],
            ],
            [
                'class' => BlockClientColumn::className(),
            ],
//            [
//                'class' => 'yii\grid\ActionColumn',
//                'template' => '{view-client} {delete-client}',
//                'buttons' => [
//                    'view-client' => function ($url, $data) {
//                        return 
//                            Html::a('Просмотр', 
//                                    [
//                                        'clients/view-client',
//                                        'client_id' => $data['client_id'],
//                                        'account_number' => $data['number'],
//                                    ], 
//                                    [
//                                        'data-pjax' => false,
//                                        'class' => 'btn btn-info btn-sm',
//                                    ]
//                            );
//                    },
//                    'delete-client' => function ($url, $data) {
//                        return 
//                            Html::a('Удалить', ['clients/delete-client', 'client_id' => $data['client_id']], [
//                                'data-pjax' => false,
//                                'class' => 'btn btn-danger btn-sm',
//                            ]);
//                    },
//                ],
//            ],
        ],
    ]); ?>
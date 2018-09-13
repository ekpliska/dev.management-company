<?php
    use yii\grid\GridView;
    use yii\helpers\Url;
    use app\helpers\FormatHelpers;
    use app\modules\managers\components\BlockClientColumn;

/*
 * Вывод таблицы заявок текущего пользователя
 */
?>
    <?= GridView::widget([
        'dataProvider' => $client_list,
        'filterUrl' => Url::to(['requests/index']),
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'surname',
                'header' => 'Фамилия',
            ],
            [
                'attribute' => 'name',
                'header' => 'Имя',
            ],
            [
                'attribute' => 'second_name',
                'header' => 'Отчество',
            ],
            [
                'attribute' => 'adress',
                'header' => 'Адрес',
                'value' => function ($data) {
                    return FormatHelpers::formatFullAdress($data['town'], $data['street'], $data['house'], $data['flat']);
                },
            ],
            [
                'attribute' => 'number',
                'header' => 'Лицевой счет',
            ],
            [
                'attribute' => 'balance',
                'header' => 'Баланс',
                'value' => function ($data) {
                    return FormatHelpers::formatBalance($data['balance']);
                },
                'format' => 'raw',
            ],
            [
                'class' => BlockClientColumn::className(),
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'controller' => 'requests/block-client',
                'template' => '{view-request}',
                'buttons' => [
                    'view-request' => function ($url, $data) {
                        $url = ['request_numder' => $data['client_id']];
                        return '<a href="#" data-pjax="0" class="btn btn-info">Подробнее</a>';
                    },
                ],
            ],
        ],
    ]); ?>
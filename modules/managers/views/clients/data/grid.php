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
        'rowOptions' => function ($data, $key, $index, $grid) {
            if ($data['balance'] < 0) {
                return ['style' => 'background: #fce7df'];
            }
        },
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
                            $data['second_name'] . '<br />' . 
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
                    return ($data['full_adress'] && $data['flat']) ? "{$data['full_adress']} , кв. {$data['flat']}" : "Не указан";
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
                'visible' => Yii::$app->user->can('ClientsEdit') ? true : false,
            ],
        ],
    ]); ?>
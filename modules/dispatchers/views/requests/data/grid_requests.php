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
        'dataProvider' => $results,
        'layout' => '{items}{pager}',
        'tableOptions' => [
            'class' => 'table dispatcher-table',
        ],
        'columns' => [
            [
                'attribute' => 'number',
                'header' => 'ID',
                'value' => function ($data) {
                    return Html::a($data['number'], ['requests/view-request', 'request_number' => $data['number']]);
                },
                'format' => 'raw',
                'contentOptions' =>[
                    'class' => 'dispatcher-table_small',
                ],
            ],
            [
                'attribute' => 'requests_name',
                'header' => 'Вид заявки',
                'value' => function ($data) {
                    return $data['requests_name'];
                },
                'format' => 'raw',
                'contentOptions' =>[
                    'class' => 'dispatcher-table_small',
                ],
            ],
            [
                'attribute' => 'adress',
                'header' => 'Адрес',
                'value' => function ($data) {
                    return FormatHelpers::formatFullAdress(
                            $data['gis_adress'],
                            $data['houses_street'],
                            $data['house'],
                            false, false, $data['flat']
                    );
                },
                'contentOptions' =>[
                    'class' => 'dispatcher-table_middle dispatcher-table_left',
                ],
            ],
            [
                'attribute' => 'comment',
                'header' => 'Описание',
                'value' => function ($data) {
                    return FormatHelpers::shortTitleOrText($data['comment'], 170);
                },
                'contentOptions' =>[
                    'class' => 'dispatcher-table_middle dispatcher-table_left',
                ],
                'format' => 'raw',
            ],
            [
                'attribute' => 'name_s',
                'header' => 'Специалист',
                'contentOptions' => [
                    'class' => 'dispatcher-table_small',
                ],
                'value' => function ($data) {
                    return
                        FormatFullNameUser::nameEmployee($data['surname_s'], $data['name_s'], $data['sname_s'], false);
                },
                'format' => 'raw',
            ],
            [
                'attribute' => 'date_create',
                'header' => 'Дата создания',
                'contentOptions' => [
                    'class' => 'dispatcher-table_small',
                ],
                'value' => function ($data) {
                    return
                        FormatHelpers::formatDate($data['date_create'], false, 0, false);
                },
                'format' => 'raw',
            ],
            [
                'attribute' => 'date_close',
                'header' => 'Дата закрытия',
                'contentOptions' => [
                    'class' => 'dispatcher-table_small',
                ],
                'value' => function ($data) {
                    return
                        FormatHelpers::formatDate($data['date_close'], false, 0, false);
                },
                'format' => 'raw',
            ],
            [
                'attribute' => 'status',
                'header' => 'Статус',
                'value' => function ($data) {
                    return StatusHelpers::requestStatus($data['status'], $data['requests_id'], false, $data['grade']);
                },
                'format' => 'raw',
                'contentOptions' => [
                    'class' => 'dispatcher-table_small',
                ],
            ],
        ],
    ]); ?>
</div>
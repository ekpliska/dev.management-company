<?php

    use yii\grid\GridView;
    use yii\helpers\Html;
    use app\helpers\FormatHelpers;
    use app\helpers\StatusHelpers;
    use app\helpers\FormatFullNameUser;

/*
 * Вывод таблицы заявки на платные услуги пользователя
 */
?>
<div class="grid-view">
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
                    return Html::a($data['number'], ['requests/view-paid-request', 'request_number' => $data['number']]);
                },
                'format' => 'raw',
                'contentOptions' =>[
                    'class' => 'dispatcher-table_small',
                ],
            ],
            [
                'attribute' => 'requests_name',
                'header' => 'Категория <br /> Наименование услуги',
                'value' => function ($data) {
                    return $data['category'] . '<br />' . $data['service_name'];
                },
                'format' => 'raw',
                'contentOptions' =>[
                    'class' => 'dispatcher-table_small',
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
                    return StatusHelpers::requestStatus($data['status'], $data['id'], false, false);
                },
                'format' => 'raw',
                'contentOptions' => [
                    'class' => 'dispatcher-table_small',
                ],
            ],
        ],
    ]); ?>
</div>
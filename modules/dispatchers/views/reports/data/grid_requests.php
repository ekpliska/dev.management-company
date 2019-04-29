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
<div class="grid-view">
    <?= GridView::widget([
        'dataProvider' => $results,
        'layout' => '{items}{pager}',
        'tableOptions' => [
            'class' => 'table dispatcher-table-report',
        ],
        'columns' => [
            [
                'attribute' => 'number',
                'header' => 'ID',
                'value' => function ($data) {
                    return Html::a($data['number'], ['requests/view-request', 'request_number' => $data['number']]);
                },
                'format' => 'raw',
            ],
            [
                'attribute' => 'date_create',
                'header' => 'Дата создания',
                'value' => function ($data) {
                    return
                        FormatHelpers::formatDate($data['date_create'], false, 0, false);
                },
                'format' => 'raw',
            ],
            [
                'attribute' => 'requests_name',
                'header' => 'Вид заявки',
                'value' => function ($data) {
                    return $data['requests_name'];
                },
                'format' => 'raw',
            ],
            [
                'attribute' => 'name_s',
                'header' => 'Специалист',
                'value' => function ($data) {
                    return
                        FormatFullNameUser::nameEmployee($data['surname_s'], $data['name_s'], $data['sname_s'], false);
                },
                'format' => 'raw',
            ],
            [
                'attribute' => 'date_close',
                'header' => 'Дата закрытия',
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
                    return StatusHelpers::reportStatus($data['status']);
                },
                'format' => 'raw',
            ],
        ],
    ]); ?>
</div>
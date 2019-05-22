<?php

    use yii\helpers\Html;
    use yii\grid\GridView;
    use yii\widgets\Pjax;
    use app\helpers\FormatHelpers;

/* 
 * Заявки, новые
 */
?>

<div class="__request_block-content">
    
    <?php Pjax::begin([
        'enablePushState' => false,
    ]) ?>
    <?= GridView::widget([
            'dataProvider' => $request_list,
            'layout' => '{items}{pager}',
            'tableOptions' => [
                'class' => 'table table-request',
            ],
            'columns' => [
                [
                    'attribute' => 'number',
                    'header' => 'Заявка, Вид',
                    'value' => function ($data) {
                        return Html::a("ID{$data['number']}", ['requests/view-request', 'request_number' => $data['number']]) .
                                '<span>' . $data['type_requests'] . '</span>';
                    },
                    'format' => 'raw',
                ],
                [
                    'attribute' => 'date_create',
                    'header' => 'Дата создания',
                    'value' => function ($data) {
                        return Yii::$app->formatter->asDate($data['date_create'], 'dd/MM/y');
                    },
                            
                ],
                [
                    'attribute' => 'requests_comment',
                    'header' => 'Описание',
                    'value' => function ($data) {
                        return FormatHelpers::shortTextNews($data['requests_comment'], 7);;
                    },
                ],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'visible' => Yii::$app->user->can('RequestsEdit') ? true : false,
                    'template' => '{settings-request}',
                    'buttons' => [
                        'settings-request' => function ($url, $data) {
                            return Html::a('<i class="fa fa-pencil-square-o" aria-hidden="true"></i>', 
                                    ['requests/view-request', 'request_number' => $data['number']], [
                                        'class' => 'table-request__btn-view'
                                    ]);
;
                        },
                    ],
                    'contentOptions' => [
                            'class' => 'table-request__settings',
                        ],
                    ],
            ],
        ]);
    ?>
    <?php Pjax::end() ?>
    
</div>
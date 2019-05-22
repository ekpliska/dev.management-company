<?php

    use yii\helpers\Html;
    use yii\grid\GridView;
    use yii\widgets\Pjax;
    use app\helpers\FormatHelpers;

/* 
 * Заявки на платные усуги, новые
 */
?>

<div class="__request_block-content">
    
    <?php Pjax::begin([
        'enablePushState' => false,
    ]) ?>
    <?= GridView::widget([
            'dataProvider' => $paid_request_list,
            'layout' => '{items}{pager}',
            'tableOptions' => [
                'class' => 'table table-request',
            ],
            'columns' => [
                [
                    'attribute' => 'number',
                    'header' => 'Заявка, Услуга',
                    'value' => function ($data) {
                        return Html::a("ID{$data['number']}", ['paid-requests/view-paid-request', 'request_number' => $data['number']]) .
                                '<span>' . $data['service_name'] . '</span>';
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
                        return FormatHelpers::shortTextNews($data['comment'], 7);;
                    },
                ],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'visible' => Yii::$app->user->can('RequestsEdit') ? true : false,
                    'template' => '{settings-request}',
                    'buttons' => [
                        'settings-request' => function ($url, $data) {
                            return Html::a('<i class="fa fa-pencil-square-o" aria-hidden="true"></i>', 
                                    ['paid-requests/view-paid-request', 'request_number' => $data['number']], [
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
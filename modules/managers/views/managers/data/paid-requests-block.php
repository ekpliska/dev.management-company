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
                    'header' => 'Заявка, Категория',
                    'value' => function ($data) {
                        return Html::a("ID{$data['number']}", ['paid-requests/view-paid-request', 'request_number' => $data['number']]) .
                                '<span>' . $data['category'] . '</span>' .
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





<?php /* if (!empty($paid_request_list) && count($paid_request_list) > 0) : ?>
    <?php foreach ($paid_request_list as $paid_request) : ?>
        <div class="general-right__request-body">
            <h5>
                <?= Html::a("ID {$paid_request['number']}", ['paid-requests/view-paid-request', 'request_number' => $paid_request['number']], [
                        'class' => 'requiest-id']) ?>
                
                <span class="requiest-date pull-right">
                    <?= FormatHelpers::formatDate($paid_request['date_create'], true, 0, false) ?>
                </span>
            </h5>
            <div>
                <p><span class="title">Категория, услуга: </span><?= "{$paid_request['category']}, {$paid_request['service_name']}" ?></p>
                <p><span class="title">Адрес:</span> 
                    <?=
                    FormatHelpers::formatFullAdress(
                            $paid_request['gis_adress'], $paid_request['house'], false, false, $paid_request['flat'])
                    ?>
                </p>
            </div>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <div class="notice info">
        <p>Новых заявок на платные услуги нет.</p>
    </div>
<?php endif; */ ?>
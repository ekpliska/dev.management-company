<?php

    use yii\helpers\Html;
    use yii\grid\GridView;
    use yii\widgets\Pjax;
    use app\helpers\FormatHelpers;

/* 
 * Последние 10 новых заявок
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

<?php /* if (!empty($request_list) && count($request_list) > 0) : ?>
    <?php foreach ($request_list as $request) : ?>
        <div class="general-right__request-body">
            <h5>
                <?= Html::a("ID {$request['number']}", ['requests/view-request', 'request_number' => $request['number']], ['class' => 'requiest-id']) ?>
                <span class="requiest-date pull-right">
                    <?= FormatHelpers::formatDate($request['date_create'], true, 0, false) ?>
                </span>
            </h5>
            <div>
                <p><span class="title">Вид заявки: </span><?= $request['type_requests'] ?></p>
                <p><span class="title">Адрес:</span> 
                    <?=
                    FormatHelpers::formatFullAdress(
                            $request['gis_adress'], $request['house'], false, false, $request['flat'])
                    ?>
                </p>
            </div>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <div class="notice info">
        <p>Новых заявок нет.</p>
    </div>
<?php endif; */ ?>
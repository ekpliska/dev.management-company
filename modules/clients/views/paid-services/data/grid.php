<?php

    use yii\grid\GridView;
    use yii\helpers\Html;
    use app\helpers\FormatHelpers;
    use yii\widgets\Pjax;
    use app\helpers\StatusHelpers;
    
/*
 * Вывод истории платных услуг
 */    
?>

<div class="grid-view">
    
    <?php Pjax::begin() ?>
    
        <?= GridView::widget([
            'dataProvider' => $all_orders,
            'layout' => '{items}{pager}',
            'tableOptions' => [
                'class' => 'table req-table  pay-table account-info-table px-0',
            ],
            'columns' => [
                [
                    'attribute' => 'services_number',
                    'label' => 'Номер',
                    'value' => 'services_number',
                    'headerOptions' => [
                        'style' => 'width: 10%',
                    ],
                    'contentOptions' =>[
                        'class' => 'req-table_req-main',
                    ],
                ],
                [
                    'attribute' => 'category_name',
                    'label' => 'Категория',
                    'value' => 'category_name',
                    'headerOptions' => [
                        'style' => 'width: 15%',
                    ],
                    'contentOptions' =>[
                        'class' => 'req-table_req-type',
                    ],
                ],
                [
                    'attribute' => 'services_name',
                    'label' => 'Наименование услуги',
                    'value' => 'services_name',
                    'headerOptions' => [
                        'style' => 'width: 15%',
                    ],
                    'contentOptions' =>[
                        'class' => 'req-table_req-type',
                    ],
                ],
                [
                    'attribute' => 'created_at',
                    'label' => 'Дата заявки',
                    'headerOptions' => [
                        'style' => 'width: 10%',
                    ],                    
                    'format' => ['date', 'php:d.m.Y'],
                    'contentOptions' =>[
                        'class' => 'req-table_req-main',
                    ],
                ],
                [
                    'attribute' => 'services_comment',
                    'label' => 'Текст заявки',
                    'headerOptions' => [
                        'style' => 'width: 20%',
                    ],                    
                    'contentOptions' =>[
                        'class' => 'req-table-description-request',
                    ],
                ],
                [
                    'attribute' => 'services_specialist_id',
                    'label' => 'Исполнитель',
                    'value' => 'services_specialist_id',
                    'headerOptions' => [
                        'style' => 'width: 10%',
                    ],                    
                    'contentOptions' =>[
                        'class' => 'req-table_req-main',
                    ],
                ],
                [
                    'attribute' => 'status',
                    'label' => 'Статус',
                    'value' => function ($data) {
                        return StatusHelpers::requestStatus($data['status']);
                    },
                    'headerOptions' => [
                        'style' => 'width: 10%',
                    ],                            
                    'contentOptions' =>[
                        'class' => 'req-table_req-main',
                    ],
                    'format' => 'raw',
                ],
                [
                    'attribute' => 'updated_at',
                    'label' => 'Дата закрытия',
                    'format' => ['date', 'php:d.m.Y'],
                    'headerOptions' => [
                        'style' => 'width: 10%',
                    ],                    
                    'contentOptions' =>[
                        'class' => 'req-table_req-main',
                    ],
                ],
            ],
        ]); ?>
    
    <?php Pjax::end() ?>
    
</div>
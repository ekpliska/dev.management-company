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
                'class' => 'table clients-table',
            ],
            'pager' => [
                'prevPageLabel' => '<i class="glyphicon glyphicon-menu-left"></i>',
                'nextPageLabel' => '<i class="glyphicon glyphicon-menu-right"></i>',
            ],            
            'columns' => [
                [
                    'attribute' => 'services_number',
                    'label' => 'ID',
                    'value' => 'services_number',
                    'contentOptions' =>[
                        'class' => 'clients-table_main',
                    ],
                ],
                [
                    'attribute' => 'category_name',
                    'header' => 'Категория, <br /> Наименование услуг',
                    'value' => 'category_name',
                    'value' => function ($data) {
                        return $data['category_name'] . ', <br />' . $data['service_name'];
                    },
                    'format' => 'raw',
                    'contentOptions' =>[
                        'class' => 'requests-table_category',
                    ],
                ],
                [
                    'attribute' => 'created_at',
                    'label' => 'Дата заявки',
                    'format' => ['date', 'php:d.m.Y'],
                    'contentOptions' =>[
                        'class' => 'clients-table_main',
                    ],
                ],
                [
                    'attribute' => 'services_comment',
                    'label' => 'Текст заявки',
                    'contentOptions' =>[
                        'class' => 'clients-table_description',
                    ],
                ],
                [
                    'attribute' => 'services_specialist_id',
                    'label' => 'Исполнитель',
                    'value' => 'services_specialist_id',
                    'contentOptions' =>[
                        'class' => 'clients-table_main',
                    ],
                ],
                [
                    'attribute' => 'status',
                    'label' => 'Статус',
                    'value' => function ($data) {
                        return StatusHelpers::requestStatus($data['status']);
                    },
                    'contentOptions' =>[
                        'class' => 'clients-table_main',
                    ],
                    'format' => 'raw',
                ],
                [
                    'attribute' => 'updated_at',
                    'label' => 'Дата закрытия',
                    'format' => ['date', 'php:d.m.Y'],
                    'contentOptions' =>[
                        'class' => 'clients-table_main',
                    ],
                ],
            ],
        ]); ?>
    
    <?php Pjax::end() ?>
    
</div>
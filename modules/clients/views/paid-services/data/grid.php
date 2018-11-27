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
                'class' => 'table requests-table',
            ],
            'columns' => [
                [
                    'attribute' => 'services_number',
                    'label' => 'ID',
                    'value' => 'services_number',
                    'contentOptions' =>[
                        'class' => 'requests-table_main',
                    ],
                ],
                [
                    'attribute' => 'category_name',
                    'header' => 'Категория, <br /> Наименование услуг',
                    'value' => 'category_name',
                    'value' => function ($data) {
                        return $data['category_name'] . ', <br />' . $data['services_name'];
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
                        'class' => 'requests-table_main',
                    ],
                ],
                [
                    'attribute' => 'services_comment',
                    'label' => 'Текст заявки',
                    'contentOptions' =>[
                        'class' => 'requests-table_description ',
                    ],
                ],
                [
                    'attribute' => 'services_specialist_id',
                    'label' => 'Исполнитель',
                    'value' => 'services_specialist_id',
                    'contentOptions' =>[
                        'class' => 'requests-table_main',
                    ],
                ],
                [
                    'attribute' => 'status',
                    'label' => 'Статус',
                    'value' => function ($data) {
                        return StatusHelpers::requestStatus($data['status']);
                    },
                    'contentOptions' =>[
                        'class' => 'requests-table_main',
                    ],
                    'format' => 'raw',
                ],
                [
                    'attribute' => 'updated_at',
                    'label' => 'Дата закрытия',
                    'format' => ['date', 'php:d.m.Y'],
                    'contentOptions' =>[
                        'class' => 'requests-table_main',
                    ],
                ],
            ],
        ]); ?>
    
    <?php Pjax::end() ?>
    
</div>
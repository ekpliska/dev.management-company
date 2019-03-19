<?php

    use yii\grid\GridView;
    use app\helpers\FormatFullNameUser;
    use yii\widgets\Pjax;
    use app\helpers\StatusHelpers;
    use app\helpers\FormatHelpers;
    use app\modules\clients\widgets\GradePaidService;
    
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
                    'attribute' => 'Исполнитель',
                    'value' => function ($data) {
                        return FormatFullNameUser::nameEmployee(
                            $data['employee_surname'], 
                            $data['employee_name'], 
                            $data['employee_second_name'], 
                            false);
                    },
                    'format' => 'raw',
                ],
                [
                    'attribute' => 'status',
                    'label' => 'Статус',
                    'value' => function ($data) {
                        return StatusHelpers::requestStatus($data['status'], $data['services_number'], true, null);
                    },
                    'contentOptions' =>[
                        'class' => 'clients-table_main',
                    ],
                    'format' => 'raw',
                ],
                [
                    'attribute' => 'updated_at',
                    'label' => 'Дата закрытия',
                    'value' => function ($data) {
                        return FormatHelpers::formatDate($data['updated_at'], false, 0, false) . 
                                GradePaidService::widget([
                                    'request_id' => $data['services_id'], 
                                    'request_status' => $data['status'],
                                    'request_grade' => $data['grade']]);
                    },
                    'format' => 'raw',
                    'contentOptions' =>[
                        'class' => 'clients-table_main',
                    ],
                ],
            ],
        ]); ?>
    
    <?php Pjax::end() ?>
    
</div>
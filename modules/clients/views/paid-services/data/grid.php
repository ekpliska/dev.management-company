<?php

    use yii\grid\GridView;
    use app\helpers\FormatHelpers;
    use yii\widgets\Pjax;    
    
/*
 * Вывод истории платных услуг
 */    
?>

<div class="grid-view">
    
    <?php Pjax::begin() ?>
    
        <?= GridView::widget([
            'dataProvider' => $all_orders,
            'columns' => [
                [
                    'attribute' => 'services_number',
                    'label' => 'Номер',
                    'value' => 'services_number',
                ],
                [
                    'attribute' => 'category_name',
                    'label' => 'Категория',
                    'value' => 'category_name',
                ],
                [
                    'attribute' => 'services_name',
                    'label' => 'Наименование услуги',
                    'value' => 'services_name',
                ],
                [
                    'attribute' => 'created_at',
                    'label' => 'Дата заявки',
                    'format' => ['date', 'php:d.m.Y H:m:i'],
                ],
                            
                            
                [
                    'attribute' => 'services_comment',
                    'label' => 'Текст заявки',
                    'value' => function ($data) {
                        return 
                            '<div class="cutstring" data-display="none" data-max-length="70">'
                                . $data['services_comment']
                            . '</div>';
                    },
                    'format' => 'raw',
                ],
                [
                    'attribute' => 'services_specialist_id',
                    'label' => 'Исполнитель',
                    'value' => 'services_specialist_id',
                ],
                [
                    'attribute' => 'status',
                    'label' => 'Статус',
                    'value' => function ($data) {
                        return FormatHelpers::statusName($data['status']);
                    },
                ],
                [
                    'attribute' => 'updated_at',
                    'label' => 'Дата закрытия',
                    'format' => ['date', 'php:d.m.Y'],
                ],
            ],
        ]); ?>
    
    <?php Pjax::end() ?>
    
</div>
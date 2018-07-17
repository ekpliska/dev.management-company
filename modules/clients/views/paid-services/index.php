<?php
    use yii\grid\GridView;
/* 
 * Заявки (Обзая страница)
 */
$this->title = 'История услуг';
?>
<div class="paid-services-default-index">
    <h1><?= $this->title ?></h1>
    
    <?= GridView::widget([
        'dataProvider' => $all_orders,
        'columns' => [
            
            'services_number',
            [
                'attribute' => 'Категория',
                'value' => function ($data) {
                    return $data->getNameCategory();
                },
            ],
            [
                'attribute' => 'services_name_services_id',
                'value' => function ($data) {
                    return $data->getNameServices();
                },
            ],
            [
                'attribute' => 'created_at',
                'value' => function ($data) {
                    return Yii::$app->formatter->asDate($data->created_at, 'php:d.m.Y H:m:i');
                },
            ],
            'services_comment',
            'services_specialist_id',
            [
                'attribute' => 'status',
                'value' => function ($data) {
                    return $data->getStatusName();
                },
            ],
            [
                'attribute' => 'updated_at',
                'value' => function ($data) {
                    return $data->status == 4 ? Yii::$app->formatter->asDate($data->updated_at, 'php:d.m.Y H:m:i') : '';
                },
            ],
        ],
    ]); ?>
    
</div>

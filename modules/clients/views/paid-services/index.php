<?php
    use yii\grid\GridView;
    use app\helpers\FormatHelpers;
    
/* 
 * Заявки (Обзая страница)
 */
$this->title = 'История услуг';
?>
<div class="paid-services-default-index">
    <h1><?= $this->title ?></h1>
    
    // UPD: В платных заявках иправить связь сформированных заявок
    // Связь по лицевой_счет_ИД
    
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
                'value' => function ($data) {
                    return FormatHelpers::formatDateCounter($data['created_at']);
                },
            ],
            [
                'attribute' => 'services_comment',
                'label' => 'Текст заявки',
                'value' => 'services_comment',
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
                'value' => function ($data) {
                    return FormatHelpers::formatDateCounter($data['updated_at']);
                },
            ],
        ],
    ]); ?>
    
</div>

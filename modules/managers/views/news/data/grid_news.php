<?php

    use yii\grid\GridView;
    use yii\helpers\Html;
    use app\helpers\FormatHelpers;

/*
 * Вывод таблицы все новости
 */
?>
<div class="grid-view">
    <?= GridView::widget([
        'dataProvider' => $all_news,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'date',
                'header' => 'Дата публикации',
                'value' => function ($date){
                    return FormatHelpers::formatDate($date['date'], true);
                },
            ],
            [
                'attribute' => 'house',
                'value' => function ($data) {
                    if (!empty($data['estate_name'])) {
                        return $data['estate_name'];
                    } elseif ($data['town']) {
                        return FormatHelpers::formatFullAdress($data['town'], $data['street'], $data['house']);
                    }
                    return '<span class="label label-default">Для всех</span>';
                },
                'format' => 'raw',
            ],
            [
                'attribute' => 'rubric',
                'header' => 'Заголовок публикации',
                'value' => function($data) {
                    return $data['title'] . '<br /><span class="label label-success">' . $data['rubric'] . '</span>';
                },
                'format' => 'raw',
            ],
            [
                'attribute' => 'text',
                'header' => 'Тизер',
                'value' => function ($data){
                    return FormatHelpers::shortTextNews($data['text'], 15);
                },
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view-news} {delete-news}',
                'buttons' => [
                    'view-news' => function ($url, $data) {                        
                        return 
                            Html::a('Просмотр', 
                                    [
                                        'news/view-news',
                                        'slug' => $data['slug'],
                                    ], 
                                    [
                                        'data-pjax' => false,
                                        'class' => 'btn btn-info btn-sm',
                                    ]
                            );
                    },
                    'delete-news' => function ($url, $data) {
                        return 
                            Html::button('Удалить', [
                                'data-pjax' => false,
                                'class' => 'btn btn-danger btn-sm',
                                'data-target' => '#delete_news_manager',
                                'data-toggle' => 'modal',
                                'data-news' => $data['id'],
                            ]);
                    },
                ],
            ],
        ],
    ]); ?>
</div>
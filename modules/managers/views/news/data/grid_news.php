<?php

    use yii\grid\GridView;
    use yii\helpers\Html;
    use app\helpers\FormatHelpers;
    use app\helpers\FormatFullNameUser;

/*
 * Вывод таблицы все новости
 */
?>

<?php if (isset($all_news) && !empty($all_news) && count($all_news) > 0) : ?>
<?php foreach ($all_news as $key => $news) : ?>

<div class="col-md-4">
    <div class="news-item">
        <div class="news-item__title">
            <p class="title"><?= $news['title'] ?></p>
            <p class="date"><?= FormatHelpers::formatDate($news['date'], false, 0, false) ?></p>
            <?= Html::button('<i class="glyphicon glyphicon-trash"></i>', ['class' => 'btn bnt-delete-news']) ?>
        </div>
        <div class="news-item__image">
            <span class="item__image_desc"><?= $news['rubric'] ?></span>
            <span class="item__image_desc"><?= FormatFullNameUser::nameEmployeeByUserID($news['user_id']) ?></span>
            <?= Html::img("@web/{$news['preview']}", ['class' => 'news_preview']) ?>
        </div>
        <div class="news-item__text">
            <?= FormatHelpers::shortTextNews($news['text'], 40) ?>
            <p class="change-date">
                Последний раз редактировалось, <?= FormatHelpers::formatDate($news['date_update'], true, 1, false) ?>
            </p>
        </div>
    </div>
</div>

<?php endforeach; ?>
<?php endif; ?>

<?php /*
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
                'header' => 'Адрес',
                'value' => function ($data) {
                    if ($data['status'] == 0) {
                        return '<span class="label label-default">Для всех</span>';                        
                    } elseif ($data['status'] == 1) {
                        return $data['estate_name'] . ', г. ' . $data['estate_town'];
                    } elseif ($data['status'] == 2) {
                        return FormatHelpers::formatFullAdress($data['estate_town'], $data['street'], $data['house']);
                    }
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
                'template' => '{view} {delete}',
                'buttons' => [
                    'view' => function ($url, $data) {                        
                        return 
                            Html::a('Просмотр', 
                                    [
                                        'news/view',
                                        'slug' => $data['slug'],
                                    ], 
                                    [
                                        'data-pjax' => false,
                                        'class' => 'btn btn-info btn-sm',
                                    ]
                            );
                    },
                    'delete' => function ($url, $data) {
                        return 
                            Html::button('Удалить', [
                                'data-pjax' => false,
                                'class' => 'btn btn-danger btn-sm',
                                'data-target' => '#delete_news_manager',
                                'data-toggle' => 'modal',
                                'data-news' => $data['id'],
                                'data-is-advert' => $data['advert'],
                            ]);
                    },
                ],
            ],
        ],
    ]); ?>
</div>
 * 
 */ ?>
<?php
    use yii\grid\GridView;
    use yii\helpers\Html;

/*
 * Вывод таблицы зарегистрированных пользователей с ролью Диспетчер
 */
?>
<div class="grid-view">
    <?= GridView::widget([
        'dataProvider' => $dispatchers,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'surname',
                'header' => 'Фамилия',
            ],
            [
                'attribute' => 'name',
                'header' => 'Имя',
            ],
            [
                'attribute' => 'second_name',
                'header' => 'Отчество',
            ],
            [
                'attribute' => 'login',
                'header' => 'Логин',
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{edit-dispatcher} {delete-dispatcher}',
                'buttons' => [
                    'edit-dispatcher' => function ($url, $data) {
                        return 
                            Html::a('Просмотр', 
                                    [
                                        'employers/edit-dispatcher',
                                        'dispatcher_id' => $data['id'],
                                    ], 
                                    [
                                        'data-pjax' => false,
                                        'class' => 'btn btn-info btn-sm',
                                    ]
                            );
                    },
                    'delete-dispatcher' => function ($url, $data) {
                        return 
                            Html::a('Удалить', ['delete-dispatchers', 'employer_id' => $data['id']], [
                                'data-pjax' => false,
                                'class' => 'btn btn-danger btn-sm',
                            ]);
                    },
                ],
            ],
        ],
    ]); ?>
</div>
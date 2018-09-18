<?php
    use yii\grid\GridView;
    use yii\helpers\Html;

/*
 * Вывод таблицы зарегистрированных пользователей с ролью Диспетчер
 */
?>
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
                'template' => '{edit-client} {delete-client}',
                'buttons' => [
                    'view-client' => function ($url, $data) {
                        return 
                            Html::a('Просмотр', 
                                    [
                                        'dispatchers/edit-client',
                                        'employer_id' => $data['id'],
                                    ], 
                                    [
                                        'data-pjax' => false,
                                        'class' => 'btn btn-info btn-sm',
                                    ]
                            );
                    },
                    'delete-client' => function ($url, $data) {
                        return 
                            Html::a('Удалить', ['dispatchers/delete-client', 'client_id' => $data['id']], [
                                'data-pjax' => false,
                                'class' => 'btn btn-danger btn-sm',
                            ]);
                    },
                ],
            ],
        ],
    ]); ?>
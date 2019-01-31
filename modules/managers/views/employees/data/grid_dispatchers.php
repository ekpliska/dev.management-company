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
        'layout' => '{items}{pager}',
        'tableOptions' => [
            'class' => 'table managers-table',
        ],
        'columns' => [
//            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'employee_id',
                'header' => 'ID',
                'contentOptions' =>[
                    'class' => 'managers-table_small',
                ],
            ],
            [
                'header' => 'Фамилия <br /> имя отчество',
                'value' => function ($data) {
                    return $data['employee_surname'] . ' ' .
                            $data['employee_name'] . ' ' .
                            $data['employee_second_name'];
                },
                'contentOptions' => [
                    'class' => 'managers-managers-table_big managers-table_left',
                ],
            ],            
            [
                'attribute' => 'employee_department_id',
                'header' => 'Подразделение',
            ],                        
            [
                'attribute' => 'post_name',
                'header' => 'Должность',
            ],
            [
                'attribute' => 'user_login',
                'header' => 'Логин',
            ],
            [
                'attribute' => 'last_login',
                'header' => 'Последний раз был',
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'contentOptions' =>[
                    'class' => 'managers-table_big',
                ],
                'template' => '{edit-dispatcher} {delete-dispatcher}',
                'buttons' => [
                    'edit-dispatcher' => function ($url, $data) {                        
                        return 
                            Html::a('Просмотр', 
                                    [
                                        'employee-form/employee-profile',
                                        'type' => 'dispatcher',
                                        'employee_id' => $data->employee_id,
                                    ], 
                                    [
                                        'data-pjax' => false,
                                        'class' => 'btn-sm btn-edit-in-table',
                                    ]
                            );
                    },
                    'delete-dispatcher' => function ($url, $data) {
                        $full_name = $data->employee_surname . ' ' . $data->employee_name . ' ' . $data->employee_second_name;
                        return 
                            Html::button('Удалить', [
                                'data-pjax' => false,
                                'class' => 'btn-sm btn-delete-in-table',
                                'data-target' => '#delete_employee_manager',
                                'data-toggle' => 'modal',
                                'data-role' => 'dispatcher',
                                'data-employee' => $data->employee_id,
                                'data-full-name' => $full_name,
                            ]);
                    },
                ],
            ],
        ],
    ]); ?>
</div>
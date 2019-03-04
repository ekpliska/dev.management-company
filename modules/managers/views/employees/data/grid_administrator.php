<?php

    use yii\grid\GridView;
    use yii\helpers\Html;
    use app\helpers\FormatHelpers;

/* 
 * Рендер таблицы Администраторы
 */
?>

<div class="grid-managers">
    <?= GridView::widget([
        'dataProvider' => $manager_list,
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
                'attribute' => 'department_name',
                'header' => 'Подразделение',
            ],                        
            [
                'attribute' => 'post_name',
                'header' => 'Должность',
            ],                        
            [
                'attribute' => 'login',
                'header' => 'Логин',
            ],
            [
                'attribute' => 'last_login',
                'header' => 'Последний раз был',
                'value' => function ($data) {
                    return FormatHelpers::formatDate($data['last_login'], true, 1, false);
                },
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'visible' => Yii::$app->user->can('EmployeesEdit') ? true : false,
                'contentOptions' =>[
                    'class' => 'managers-table_middle managers-table_button',
                ],
                'template' => '{edit-dispatcher} {delete-dispatcher}',
                'buttons' => [
                    'edit-dispatcher' => function ($url, $data) {                        
                        return 
                            Html::a('Просмотр', 
                                    [
                                        'employee-form/employee-profile',
                                        'type' => 'administrator',
                                        'employee_id' => $data['employee_id'],
                                    ], 
                                    [
                                        'data-pjax' => false,
                                        'class' => 'btn btn-sm btn-edit-in-table',
                                    ]
                            );
                    },
                    'delete-dispatcher' => function ($url, $data) {
                        $full_name = $data['employee_surname'] . ' ' . $data['employee_name'] . ' ' . $data['employee_second_name'];
                        return 
                            Html::button('Удалить', [
                                'data-pjax' => false,
                                'class' => 'btn btn-sm btn-delete-in-table',
                                'data-target' => '#delete_employee_manager',
                                'data-toggle' => 'modal',
                                'data-role' => 'administrator',
                                'data-employee' => $data['employee_id'],
                                'data-full-name' => $full_name,
                            ]);
                    },
                ],
            ],
        ],
    ]); ?>
</div>
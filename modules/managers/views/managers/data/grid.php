<?php

    use yii\grid\GridView;
    use yii\helpers\Html;

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
                'attribute' => 'id',
                'header' => 'ID',                
            ],
            [
                'header' => 'Фамилия <br /> имя отчество',
                'value' => function ($data) {
                    return $data['surname'] . ' ' .
                            $data['name'] . ' ' .
                            $data['second_name'] . '<br />';
                },
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
                        $full_name = $data['surname'] . ' ' . $data['name'] . ' ' . $data['second_name'];
                        return 
                            Html::button('Удалить', [
                                'data-pjax' => false,
                                'class' => 'btn btn-danger btn-sm delete_dispatcher',
                                'data-target' => '#delete_disp_manager',
                                'data-toggle' => 'modal',
                                'data-employer' => $data['id'],
                                'data-full-name' => $full_name,
                            ]);
                    },
                ],
            ],
        ],
    ]); ?>
</div>
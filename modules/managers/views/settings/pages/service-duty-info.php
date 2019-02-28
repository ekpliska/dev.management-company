<?php

    use yii\widgets\ActiveForm;
    use yii\helpers\Html;

/* 
 * Справочники
 * Подразделения, Должности
 */
?>

<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
    <h4 class="title">
        Поздразделения 
        <?= Html::button('', [
                'class' => 'add-item-settings pull-right',
                'data-target' => '#add-department-modal-form',
                'data-toggle' => 'modal',
            ]) ?>
    </h4>
    <?php
        $form_departments = ActiveForm::begin([
            'id' => 'multiple-form-departments',
            'fieldConfig' => [
                'template' => '{label}{input}',
            ],
        ]);
    ?>
    <table class="table table-striped ">
        <tbody>
            <?php foreach ($departments as $index_department => $department) : ?> 
            <tr>
                <td>
                    <?= $form_departments->field($department, "[$index_department]department_name")->input('text', ['class' => 'settings-input'])->label(false); ?>
                </td>
                <td>
                    <?= Html::button('<i class="glyphicon glyphicon-trash"></i>', [
                            'class' => 'delete-item-settings delete-department-settings',
                            'data' => [
                                'record' => $index_department,
                                'type' => 'department',
                            ]
                        ]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?= Html::submitButton('Сохранить', ['class' => 'btn save-settings-small']) ?>
    <?php ActiveForm::end(); ?>
</div>
<div class="col-lg-8 col-md-8 col-sm-6 col-xs-12">
    <h4 class="title">
        Должности
        <?= Html::button('', [
                'class' => 'add-item-settings pull-right',
                'data-target' => '#add-post-modal-form',
                'data-toggle' => 'modal',
            ]) ?>
    </h4>
    <?php
        $form_posts = ActiveForm::begin([
            'id' => 'multiple-form-posts',
            'fieldConfig' => [
                'template' => '{label}{input}',
            ],
        ]);
    ?>
    <table class="table table-striped ">
        <tbody>
            <?php foreach ($posts as $index_post => $post) : ?> 
            <tr>
                <td>
                    <?= $form_posts->field($post, "[$index_post]posts_department_id")->dropDownList($department_lists, ['class' => 'settings-input'])->label(false); ?>
                </td>
                <td>
                    <?= $form_posts->field($post, "[$index_post]post_name")->input('text', ['class' => 'settings-input'])->label(false); ?>
                </td>
                <td>
                    <?= Html::button('<i class="glyphicon glyphicon-trash"></i>', [
                            'class' => 'delete-item-settings delete-department-settings',
                            'data' => [
                                'record' => $index_post,
                                'type' => 'post',
                            ]
                        ]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?= Html::submitButton('Сохранить', ['class' => 'btn save-settings-small']) ?>
    <?php ActiveForm::end(); ?>
</div>
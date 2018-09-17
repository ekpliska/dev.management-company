<?php

    use yii\widgets\ActiveForm;
    use yii\helpers\ArrayHelper;

/* 
 * Форма
 * 
 * Новый диспетчер
 */
$this->title = 'Диспетчер (+)';
?>
<div class="dispatchers-default-index">
    <h1><?= $this->title ?></h1>
    <hr />
    
    <?php
        $form = ActiveForm::begin([
            'id' => 'add-dispatcher',
        ]);
    ?>
    
        <div class="col-md-4">
            <div class="panel panel-default">
                <div class="panel-heading">Информация о сотруднике</div>
                <div class="panel-body">
                    
                    <?= $form->field($model, 'surname')
                            ->input('text', [
                                'placeHolder' => $model->getAttributeLabel('surname'),])
                            ->label() ?>
                    
                    <?= $form->field($model, 'name')
                            ->input('text', [
                                'placeHolder' => $model->getAttributeLabel('name'),])
                            ->label() ?>
                    
                    <?= $form->field($model, 'second_name')
                            ->input('text', [
                                'placeHolder' => $model->getAttributeLabel('second_name'),])
                            ->label() ?>
                    <?= $form->field($model, 'gender')
                            ->radioList(ArrayHelper::map($gender_list, 'id', 'name'))->label(); ?>
                    
                    <?= $form->field($model, 'department')
                            ->dropDownList($department_list, [
                                'class' => 'form-control department_list',
                                'prompt' => 'Выберите подразделение из списка...',])
                            ->label() ?>

                    <?= $form->field($model, 'post')
                            ->dropDownList($post_list, [
                                'prompt' => 'Выберите должность из списка...',
                                'class' => 'form-control posts_list',])
                            ->label() ?>

                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="panel panel-default">
                <div class="panel-heading">Фотография</div>
                <div class="panel-body">
                    фото
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="panel panel-default">
                <div class="panel-heading">Информация о пользователе</div>
                <div class="panel-body">
                    фото
                </div>
            </div>
        </div>
    
    <?php ActiveForm::end() ?>
    
</div>
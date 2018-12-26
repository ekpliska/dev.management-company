<?php

    use yii\widgets\Breadcrumbs;
    use yii\widgets\ActiveForm;
    use yii\helpers\Html;
    use kartik\date\DatePicker;
    use app\modules\managers\widgets\AlertsShow;

/* 
 * Форма добавления нового сотрудника
 */
$this->title = 'Администраторы';
$this->title = Yii::$app->params['site-name-manager'] .  'Администраторы';
$this->params['breadcrumbs'][] = ['label' => 'Администраторы', 'url' => ['managers/index']];
$this->params['breadcrumbs'][] = 'Новая запись [Сотрудник]';
?>
<div class="manager-main">
    <?= Breadcrumbs::widget([
            'homeLink' => ['label' => 'ELSA | Администратор', 'url' => ['managers/index']],
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
    ]) ?>

    <?= AlertsShow::widget() ?>
    
    <div class="profile-page">
        <?php
            $form = ActiveForm::begin([
                'id' => 'create-employee-form',
                'validateOnChange' => false,
                'validateOnBlur' => false,
                'options' => [
                    'enctype' => 'multipart/form-data',
                    'data-pjax' => true,
                ],
                'fieldConfig' => [
                    'template' => '<div class="field"></i>{label}{input}{error}</div>',
                ],
            ]);
        ?>

        <div class="profile-bg text-center">
            <div class="face-container">
                <?= Html::img('@web/images/new-user.png', [
                        'class' => 'img-rounded face',
                        'id' => 'photoPreview',
                        'alt' => 'user-photo']) ?>
            </div>
            <div class="profile-upload-btn">
                <?= $form->field($model, 'photo', ['template' => '<label class="text-center btn btn-upload" role="button">{input}{label}{error}'])
                        ->input('file', ['id' => 'btnLoad', 'class' => 'hidden'])->label('Загрузить фото') ?>
            </div>   
        </div>
    
        
        <div class="row">

            <!--Пользователь-->
            <div class="col-md-6 clients-profile-info_manager">
                <p class="profile-title">
                    Данные пользователя
                </p>
                <?= $form->field($model, 'username')
                        ->input('text', ['class' => 'field-input'])
                        ->label($model->getAttributeLabel('username'), ['class' => 'field-label']) ?>
                <?= $form->field($model, 'mobile')
                        ->input('text', ['class' => 'field-input cell-phone'])
                        ->label($model->getAttributeLabel('mobile'), ['class' => 'field-label']) ?>
                
                <?= $form->field($model, 'email')
                        ->input('text', ['class' => 'field-input'])
                        ->label($model->getAttributeLabel('email'), ['class' => 'field-label']) ?>
                
                <?= $form->field($model, 'password')
                        ->input('password', ['class' => 'field-input'])
                        ->label($model->getAttributeLabel('password'), ['class' => 'field-label']) ?>
                
                <?= $form->field($model, 'password_repeat')
                        ->input('password', ['class' => 'field-input'])
                        ->label($model->getAttributeLabel('password_repeat'), ['class' => 'field-label']) ?>
                    
                
            </div>


            <!--Сотрудник-->
            <div class="col-md-6 rent-profile-info_manager">
                <p class="profile-title">
                    Контактные данные сотрудника
                </p>
                <div id="content-replace" class="form-add-rent">
                <?= $form->field($model, 'surname')
                        ->input('text', ['class' => 'field-input'])
                        ->label($model->getAttributeLabel('surname'), ['class' => 'field-label']) ?>

                <?= $form->field($model, 'name')
                        ->input('text', ['class' => 'field-input'])
                        ->label($model->getAttributeLabel('name'), ['class' => 'field-label']) ?>

                <?= $form->field($model, 'second_name')
                        ->input('text', ['class' => 'field-input'])
                        ->label($model->getAttributeLabel('second_name'), ['class' => 'field-label']) ?>
                    
                <?= $form->field($model, 'birthday')
                        ->widget(DatePicker::className(), [
                            'id' => 'date-input',
                            'language' => 'ru',
                            'options' => [
                                'placeHolder' => 'ГГГГ-ММ-ДД',
                            ],
                            'type' => DatePicker::TYPE_COMPONENT_APPEND,
                            'pluginOptions' => [
                                'autoClose' => true,
                                'format' => 'yyyy-mm-dd',
                            ]])
                        ->label($model->getAttributeLabel('birthday'), ['class' => 'date-label']) ?>
                
                <?= $form->field($model, 'department')
                        ->dropDownList($department_list, [
                            'class' => 'field-input-select department_list',
                            'prompt' => 'Выбрать подразделение...',])
                        ->label($model->getAttributeLabel('department'), ['class' => 'field-input-select_label']) ?>

                <?= $form->field($model, 'post')
                        ->dropDownList($post_list, [
                            'prompt' => 'Выбрать должность...',
                            'class' => 'field-input-select posts_list',])
                        ->label($model->getAttributeLabel('post'), ['class' => 'field-input-select_label']) ?>
                    
                </div>
            </div>
        </div>

        <div class="spam-agree-txt text-center">
            <div class="save-btn-group mx-auto">
                <div class="text-center">
                    <?= Html::submitButton('Добавить', ['class' => 'btn blue-btn']) ?>
                </div>
            </div>

        </div>
     <?php ActiveForm::end() ?>
    
    </div> 
    
</div>
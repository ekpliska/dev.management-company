<?php

    use yii\widgets\Breadcrumbs;
    use yii\widgets\ActiveForm;
    use yii\helpers\Html;
    use app\helpers\FormatHelpers;
    use app\modules\dispatchers\widgets\AlertsShow;

/* 
 * Рендер вида, профиль диспетчера
 */
    
$this->title = Yii::$app->params['site-name-dispatcher'] . 'Профиль';
$this->params['breadcrumbs'][] = $employee_info->fullName;
?>
<div class="dispatcher-main">
    <?= Breadcrumbs::widget([
            'homeLink' => ['label' => 'ELSA | Диспетчер', 'url' => ['dispatchers/index']],
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
    ]) ?>

    <?= AlertsShow::widget() ?>
    
    <div class="row profile-page">
    
        <?php
            $form = ActiveForm::begin([
                'id' => 'profile-dispatcher',
                'enableClientValidation' => true,
                'enableAjaxValidation' => false,
                'validateOnChange' => true,
                'options' => [
                    'enctype' => 'multipart/form-data',
                ],
            ]);
        ?>
        
        <div class="profile-bg text-center">
            <div class="col-md-3 col-xs-6 face-user">
                <?= Html::img($user_info->photo, [
                        'id' => 'photoPreview',
                        'class' => 'img-rounded',
                        'alt' => $user_info->user_login,
                        'width' => 150,
                ]) ?>
                
            <div class="profile-upload-btn profile-upload-btn-employee">
                <?= $form->field($user_info, 'user_photo', [
                                'template' => '<label class="text-center btn-upload" role="button">{input}{label}{error}'])
                            ->input('file', ['id' => 'btnLoad', 'class' => 'hidden'])
                            ->label('<span class="glyphicon glyphicon-camera"></span>') ?>
            </div>
                
            </div>
            <div class="col-md-8 col-xs-6 text-left user-info-block">
                <p class="user-info-block_name">
                    <?= $employee_info->fullName ?>
                </p>

                <div class="status-block">
                    <span class="role-dispatcher">
                        <i class="glyphicon glyphicon-user"></i>&nbsp;&nbsp;Диспетчер
                    </span>
                </div>
     
                <div class="status-block">
                    <div class="title">
                        Регистрация в системе:
                        <br />
                        <?= FormatHelpers::formatDate($user_info->created_at) ?>
                    </div>
                    <div class="title">
                        Дата последнего входа:
                        <br />
                        <?= FormatHelpers::formatDate($user_info->last_login) ?>
                    </div>
                </div>
                
            </div>
        </div>
        
        <div class="row">
            <!--Пользователь-->
            <div class="col-md-6 clients-profile-info_manager">
                <p class="profile-title">
                    Данные пользователя
                </p>
                
                <div class="field">
                    <label for="user_mobile" class="field-label"><?= $user_info->getAttributeLabel('user_login') ?></label>
                    <?= Html::input('text', 'user_mobile', $user_info->user_login, ['class' => 'field-input', 'readonly' => true]) ?>
                </div>
                
                <?= $form->field($user_info, 'user_email', ['template' => '<div class="field has-label">{label}{input}{error}</div>'])
                        ->input('text', ['class' => 'field-input'])
                        ->label('<i class="fa fa-user-o"></i> ' . $user_info->getAttributeLabel('user_email'), ['class' => 'field-label']) ?>

                <div class="field">
                    <label for="user_mobile" class="field-label"><?= $user_info->getAttributeLabel('user_mobile') ?></label>
                    <?= Html::input('text', 'user_mobile', $user_info->user_mobile, ['class' => 'field-input', 'readonly' => true]) ?>
                </div>
                
            </div>


            <!--Сотрудник-->
            <div class="col-md-6 rent-profile-info_manager">
                <p class="profile-title">
                    Контактные данные сотрудника
                </p>
                <div id="content-replace" class="form-add-rent">
                    
                    <div class="field">
                        <label for="user_mobile" class="field-label"><?= $employee_info->getAttributeLabel('employee_surname') ?></label>
                        <?= Html::input('text', 'user_mobile', $employee_info->employee_surname, ['class' => 'field-input', 'readonly' => true]) ?>
                    </div>
                    
                    <div class="field">
                        <label for="user_mobile" class="field-label"><?= $employee_info->getAttributeLabel('employee_name') ?></label>
                        <?= Html::input('text', 'user_mobile', $employee_info->employee_name, ['class' => 'field-input', 'readonly' => true]) ?>
                    </div>
                    
                    <div class="field">
                        <label for="user_mobile" class="field-label"><?= $employee_info->getAttributeLabel('employee_second_name') ?></label>
                        <?= Html::input('text', 'user_mobile', $employee_info->employee_second_name, ['class' => 'field-input', 'readonly' => true]) ?>
                    </div>
                    
                    <div class="field">
                        <label for="user_mobile" class="field-label"><?= $employee_info->getAttributeLabel('employee_birthday') ?></label>
                        <?= Html::input('text', 'user_mobile', 
                                Yii::$app->formatter->asDate($employee_info->employee_birthday, 'd MMMM Y'), [
                                    'class' => 'field-input', 
                                    'readonly' => true]) ?>
                    </div>
                    
                    <div class="field">
                        <label for="user_mobile" class="field-label"><?= $employee_info->getAttributeLabel('employee_department_id') ?></label>
                        <?= Html::input('text', 'user_mobile', $employee_info->employeeDepartment->department_name, ['class' => 'field-input', 'readonly' => true]) ?>
                    </div>
                    
                    <div class="field">
                        <label for="user_mobile" class="field-label"><?= $employee_info->getAttributeLabel('employee_department_id') ?></label>
                        <?= Html::input('text', 'user_mobile', $employee_info->employeePosts->post_name, ['class' => 'field-input', 'readonly' => true]) ?>
                    </div>
                    
                </div>
            </div>
            
        </div>
        
        <div class="spam-agree-txt text-center">
            <div class="save-btn-group">
                <div class="text-center">
                    <?= Html::submitButton('Сохранить изменения', ['class' => 'btn blue-btn']) ?>
                </div>
            </div>
        </div>
        
        <?php ActiveForm::end() ?>

    </div>
    
</div>

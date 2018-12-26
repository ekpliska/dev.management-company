<?php

    use yii\widgets\Breadcrumbs;
    use yii\widgets\ActiveForm;
    use yii\helpers\Html;
    use kartik\date\DatePicker;
    use app\helpers\FormatHelpers;
    use app\modules\managers\widgets\AlertsShow;

/* 
 * Рендер вида, профиль сотрудника
 */
$this->title = 'Администраторы';
$this->title = Yii::$app->params['site-name-manager'] .  'Администраторы';
$this->params['breadcrumbs'][] = ['label' => 'Администраторы', 'url' => ['managers/index']];
$this->params['breadcrumbs'][] = $employee_info->fullName;
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
                'id' => 'edit-dispatcher',
                'enableClientValidation' => true,
                'enableAjaxValidation' => false,
                'validateOnChange' => true,
                'options' => [
                    'enctype' => 'multipart/form-data',
                ],
                'fieldConfig' => [
                    'template' => '<div class="field"></i>{label}{input}{error}</div>',
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
                <?= $form->field($user_info, 'user_photo', ['template' => '<label class="text-center btn btn-upload" role="button">{input}{label}{error}'])
                        ->input('file', ['id' => 'btnLoad', 'class' => 'hidden'])->label('Загрузить фото') ?>
            </div>
                
            </div>
            <div class="col-md-8 col-xs-6 text-left user-info-block">
                <p class="user-info-block_name">
                    <?= $employee_info->fullName ?>
                </p>

                <div class="status-block">
                    <span class="role-<?= $type ?>">
                        <i class="glyphicon glyphicon-user"></i>&nbsp;&nbsp;<?= $role ?>
                    </span>
                </div>
                
                <div class="control-block">
                    
                    <?php if ($user_info->status == 1) : ?>
                        <?= Html::button('Заблокировать', [
                                'class' => 'btn-block-user block_user',
                                'data-user' => $user_info->user_id,
                                'data-status' => 2]) 
                        ?>
                    <?php elseif ($user_info->status == 2)  : ?>
                        <?= Html::button('Разблокировать', [
                                'class' => 'btn-unblock-user block_user',
                                'data-user' => $user_info->user_id,
                                'data-status' => 1]) 
                        ?>
                    <?php endif; ?>                    
                    
                    <?= Html::button('Удалить', [
                                'class' => 'btn-delete-user',
                                'data-user' => $user_info->user_id]) 
                    ?>
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
                <?= $form->field($user_info, 'user_login')
                        ->input('text', ['class' => 'field-input'])
                        ->label($user_info->getAttributeLabel('user_login'), ['class' => 'field-label']) ?>
                <?= $form->field($user_info, 'user_email')
                        ->input('text', ['class' => 'field-input'])
                        ->label($user_info->getAttributeLabel('user_email'), ['class' => 'field-label']) ?>
                <?= $form->field($user_info, 'user_mobile')
                        ->input('text', ['class' => 'field-input'])
                        ->label($user_info->getAttributeLabel('user_mobile'), ['class' => 'field-label']) ?>
                
            </div>


            <!--Сотрудник-->
            <div class="col-md-6 rent-profile-info_manager">
                <p class="profile-title">
                    Контактные данные сотрудника
                </p>
                <div id="content-replace" class="form-add-rent">
                    <?= $form->field($employee_info, 'employee_surname')
                            ->input('text', ['class' => 'field-input'])
                            ->label($employee_info->getAttributeLabel('employee_surname'), ['class' => 'field-label']) ?>

                    <?= $form->field($employee_info, 'employee_name')
                            ->input('text', ['class' => 'field-input'])
                            ->label($employee_info->getAttributeLabel('employee_name'), ['class' => 'field-label']) ?>

                    <?= $form->field($employee_info, 'employee_second_name')
                            ->input('text', ['class' => 'field-input'])
                            ->label($employee_info->getAttributeLabel('employee_second_name'), ['class' => 'field-label']) ?>
                    
                    <?= $form->field($employee_info, 'employee_birthday')
                            ->widget(DatePicker::className(), [
                                'id' => 'date-input',
                                'language' => 'ru',
                                'options' => [
                                    'placeholder' => 'Дата рождения',
                                ],
                                'type' => DatePicker::TYPE_COMPONENT_APPEND,
                                'pluginOptions' => [
                                    'autoClose' => true,
                                    'format' => 'yyyy-mm-dd',
                                ]])
                            ->label($employee_info->getAttributeLabel('employee_birthday'), ['class' => 'date-label'])?>
                    
                    <?= $form->field($employee_info, 'employee_department_id')
                            ->dropDownList($department_list, [
                                'class' => 'field-input-select department_list',
                                'prompt' => 'Выберите подразделение из списка...',])
                            ->label($employee_info->getAttributeLabel('employee_department_id'), ['class' => 'field-input-select_label']) ?>
                    
                    <?= $form->field($employee_info, 'employee_posts_id')
                            ->dropDownList($post_list, [
                                'class' => 'field-input-select posts_list',])
                            ->label($employee_info->getAttributeLabel('employee_posts_id'), ['class' => 'field-input-select_label']) ?>
                    
                </div>
            </div>
        

            <div class="spam-agree-txt text-center">
                <div class="save-btn-group mx-auto">
                    <div class="text-center">
                        <?= Html::submitButton('Сохранить изменения', ['class' => 'btn blue-btn']) ?>
                    </div>
                </div>
            </div>
        </div>
        
        <?php ActiveForm::end() ?>
        
    </div>            
</div>

<?php /*        
    <div class="col-md-4">
        <div class="text-center">
            <?= Html::img($user_info->photo, ['id' => 'photoPreview', 'class' => 'img-circle', 'alt' => $user_info->user_login, 'width' => 150]) ?>
            <br />
            <?= $form->field($user_info, 'user_photo')->input('file', ['id' => 'btnLoad'])->label(false) ?>
        </div>
        <hr />
        <p>Логин: <?= $user_info->user_login ?></p>
        <p>Дата регистрации: <?= FormatHelpers::formatDate($user_info->created_at) ?></p>
        <p>Дата последнего логина: <?= FormatHelpers::formatDate($user_info->last_login) ?></p>
        <p>Статус: <?= $user_info->userStatus ?> </p>
        
        <?php if ($user_info->status == 1) : ?>
            <?= Html::button('Заблокировать', [
                    'class' => 'btn btn-danger btn-sm block_user',
                    'data-user' => $user_info->user_id,
                    'data-status' => 2]) 
            ?>
        <?php elseif ($user_info->status == 2)  : ?>
            <?= Html::button('Разблокировать', [
                    'class' => 'btn btn-success btn-sm block_user',
                    'data-user' => $user_info->user_id,
                    'data-status' => 1]) 
            ?>
        <?php endif; ?>
        
        <?= Html::button('Смена пароля', ['class' => 'btn btn-primary btn-sm']) ?>
    </div>
    
    <div class="col-md-8">
        <h3>Профиль</h3>
        <div class="col-md-6">
            <?= $form->field($employee_info, 'employee_surname')
                    ->input('text', [
                        'placeHolder' => $employee_info->getAttributeLabel('employee_surname')])
                    ->label() ?>

            <?= $form->field($employee_info, 'employee_name')
                    ->input('text', [
                        'placeHolder' => $employee_info->getAttributeLabel('employee_name')])
                    ->label() ?>

            <?= $form->field($employee_info, 'employee_second_name')
                    ->input('text', [
                        'placeHolder' => $employee_info->getAttributeLabel('employee_second_name')])
                    ->label() ?>
                    
            <?= $form->field($employee_info, 'employee_birthday')
                    ->widget(DatePicker::className(), [
                        'language' => 'ru',
                        'options' => [
                            'placeholder' => 'Дата рождения',
                        ],
                        'type' => DatePicker::TYPE_COMPONENT_APPEND,
                        'pluginOptions' => [
                            'autoClose' => true,
                            'format' => 'yyyy-mm-dd',
                        ]
                    ]) ?>
        </div>
        
        <div class="col-md-6">
            
            <?= $form->field($employee_info, 'employee_department_id')
                    ->dropDownList($department_list, [
                        'class' => 'form-control department_list',
                        'prompt' => 'Выберите подразделение из списка...',])
                    ->label() ?>
                    
            <?= $form->field($employee_info, 'employee_posts_id')
                    ->dropDownList($post_list, [
                        'class' => 'form-control posts_list',])
                    ->label() ?>
                    
        </div>
        
        <div class="col-md-12 text-right">
            <?= Html::button('Удалить', [
                'class' => 'btn btn-danger delete_dispatcher',
                'data-target' => '#delete_disp_manager',
                'data-toggle' => 'modal',
//                'data-employer' => $employee_info->id,
//                'data-full-name' => $employee_info->fullName,
                ]) ?>
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
        </div>
        
    </div>
    <?php ActiveForm::end() ?>
    
    
</div>
*/ ?>
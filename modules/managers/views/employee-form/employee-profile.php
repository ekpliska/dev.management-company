<?php

    use yii\widgets\Breadcrumbs;
    use yii\widgets\ActiveForm;
    use yii\helpers\Html;
    use kartik\date\DatePicker;
    use app\helpers\FormatHelpers;
    use yii\helpers\ArrayHelper;
    use app\modules\managers\widgets\AlertsShow;
    use app\modules\managers\widgets\ModalWindowsManager;
    use app\modules\managers\widgets\ConfirmChangePassword;
    use app\modules\managers\widgets\RequestBySpecialist;

/* 
 * Рендер вида, профиль сотрудника
 */
    
$array_url = [
    'administrator' => [
        'label' => 'Администраторы',
        'url' => 'managers/index',
    ],
    'dispatcher' => [
        'label' => 'Диспетчеры',
        'url' => 'employees/dispatchers',
    ],
    'specialist' => [
        'label' => 'Специалисты',
        'url' => 'employees/specialists',
    ],
];

$this->title = Yii::$app->params['site-name-manager'] . $array_url[$type]['label'];
$this->params['breadcrumbs'][] = ['label' => $array_url[$type]['label'], 'url' => [$array_url[$type]['url']]];
$this->params['breadcrumbs'][] = $employee_info->fullName;
?>
<div class="manager-main">
    <?= Breadcrumbs::widget([
            'homeLink' => ['label' => 'ELSA | Администратор', 'url' => ['managers/index']],
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
    ]) ?>

    <?= AlertsShow::widget() ?>
    
    <div class="row profile-page">
    
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
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 face-user">
                <?= Html::img($user_info->photo, [
                        'id' => 'photoPreview',
                        'class' => 'img-rounded',
                        'alt' => $user_info->user_login,
                        'width' => 150,
                ]) ?>
                
                <div class="profile-upload-btn profile-upload-btn-employee">
                <?= $form->field($user_info, 'user_photo', ['template' => '<label class="text-center btn-upload" role="button">{input}{label}{error}'])
                        ->input('file', ['id' => 'btnLoad', 'class' => 'hidden'])->label('Загрузить фото') ?>
            </div>
                
            </div>
            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8 text-left user-info-block">
                <p class="user-info-block_name">
                    <?= $employee_info->fullName ?>
                </p>

                <div class="status-block">
                    <span class="role-<?= $type ?>">
                        <i class="glyphicon glyphicon-user"></i>&nbsp;&nbsp;<?= $role ?>
                    </span>
                </div>
                
                <div class="control-block">
                    
                    <?= Html::button('Сменить пароль', [
                            'class' => 'btn-change-password',
                            'data-target' => '#change_employee_password',
                            'data-toggle' => 'modal']) 
                    ?>
                    
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
                            'data-target' => '#delete_employee_manager',
                            'data-toggle' => 'modal',
                            'data-role' => $type,
                            'data-employee' => $employee_info->id,
                            'data-full-name' => $employee_info->fullName,]) ?>
                    
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
                
                <?php if ($type == 'dispatcher') : ?>
                    <div class="dispatch-privileges-block">
                        <?= $form->field($user_info, 'permission_list', ['template' => '{input}{label}'])
                                ->checkbox(['name' => "permission_list[{$permissions_list['value']}]"], false)->label($permissions_list['name']) ?> 
                        
                    </div>
                <?php endif; ?>
                
                <div class="save-btn-group">
                    <div class="text-center">
                        <?= Html::submitButton('Сохранить изменения', ['class' => 'btn blue-btn']) ?>
                    </div>
                </div>
            </div>

        <?php if ($type == 'administrator') : ?>
            <div class="privileges-block row">
                <h3 class="title">Настройка прав доступа</h3>
                <?php if (isset($permissions_list) && !empty($permissions_list)) : ?>
                <?php foreach ($permissions_list as $key => $permission) : ?>
                    <div class="col-lg-4 col-md-4 col-sm-3 col-xs-4">
                        <div class="privileges-block__privilege">
                            <h4><?= $permission['name'] ?>
                            </h4>
                            <ul class="privileges-block__lists">
                                <?= $form->field($user_info, 'permission_list', ['template' => '{label}{input}'])
                                        ->checkboxList($permission['permission'], ['id' => "permission_lists",
                                            'item' => function($index, $label, $name, $checked, $value) use ($current_permissions) {
                                                $_checked = ArrayHelper::keyExists($value, $current_permissions) ? 'checked' : '';
                                                $return = '<li><span>' . $label . '</span>';
                                                $return .= '<label class="switch">' . '';
                                                $return .= '<input type="checkbox" name="permission_list[' . $value . ']"' . $_checked . '>';
                                                $return .= '<span class="slider round"></span>';
                                                $return .= '</label>';
                                                $return .= '</li>';
                                                return $return;
                                            }])
                                        ->label(false) ?>
                            </ul>       
                        </div>
                    </div>
                <?php endforeach; ?>
                <?php endif; ?>
            </div>
        <?php endif; ?>            
            
        </div>
        
        <?php ActiveForm::end() ?>

        <?php if ($type == 'specialist') : ?>
            <?= RequestBySpecialist::widget(['employee_id' => $employee_info->id]) ?>
        <?php endif; ?>

        
    </div>
    
</div>

<?= ModalWindowsManager::widget(['modal_view' => 'delete_employee']) ?>
<?= ConfirmChangePassword::widget(['user_id' => $user_info->user_id]) ?>
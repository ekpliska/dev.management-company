<?php
    
    use yii\widgets\ActiveForm;
    use yii\helpers\Html;
    use yii\widgets\MaskedInput;
    use kartik\date\DatePicker;
    use app\helpers\FormatHelpers;
    use app\modules\managers\widgets\AlertsShow;

/*
 * Профиль Администратора
 */    
    
$this->title = 'Профиль';
?>
<div class="managers-default-index">
    <h1><?= $this->title ?></h1>
    
    <?= Html::a('add', ['employee-form/index', 'new_employee' => 'administrator']) ?>
    
    <?= AlertsShow::widget() ?>
    
    <?php
        $form = ActiveForm::begin([
            'id' => 'edit-profile-employer',
            'enableClientValidation' => true,
            'enableAjaxValidation' => false,
            'validateOnChange' => true,
            'options' => [
                'enctype' => 'multipart/form-data',
            ],
        ]);
    ?>
        <div class="col-md-4">
            <div class="text-center">
                <?= Html::img($user_info->photo, ['id' => 'photoPreview','class' => 'img-circle', 'alt' => $user_info->username, 'width' => 150]) ?>
                <br />
                <?= $form->field($user_model, 'user_photo')->input('file', ['id' => 'btnLoad'])->label(false) ?>
            </div>
            <div class="text-left">
                <p>Логин: <?= $user_info->username ?></p>
                <p>Роль: <?= $user_info->role ?> </p>
                <p>Дата регистрации: <?= FormatHelpers::formatDate($user_info->dateRegister) ?></p>
                <p>Последний визит: <?= FormatHelpers::formatDate($user_info->lastLogin) ?></p>
                <p>Статус: <?= $user_info->getStatus() ?></p>
                <br />
                <?= Html::a('Сменить пароль', ['managers/settings-profile'], ['class' => 'btn btn-danger']) ?>
            </div>
        </div>
<?php /*        
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
            
            <?= $form->field($employee_info, 'employee_department_id')
                    ->dropDownList($department_list, [
                        'class' => 'form-control department_list',
                        'prompt' => 'Выберите подразделение из списка...',])
                    ->label() ?>
            
            <?= $form->field($employee_info, 'employee_posts_id')
                    ->dropDownList($post_list, [
                        'class' => 'form-control posts_list',])
                    ->label() ?>
*/ ?>            
            <?= $form->field($user_model, 'user_mobile')
                    ->widget(MaskedInput::className(), [
                        'mask' => '+7(999) 999-99-99'])
                    ->input('text', [
                        'placeHolder' => $user_model->getAttributeLabel('user_mobile')])
                    ->label() ?>
            
        </div>
        <div class="col-md-12 text-right">
            <?= Html::submitButton('Сохранить', [
                'class' => 'btn btn-primary']) ?>
        </div>
    <?php ActiveForm::end() ?>
</div>
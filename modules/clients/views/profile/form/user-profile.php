<?php

    use yii\helpers\Html;
    use yii\widgets\ActiveForm;

/* 
 * Настройки учетной записи пользователя
 */
?>

<?php $form = ActiveForm::begin([
    'id' => 'profile-form',
//    'action' => ['profile/update-profile'],
    'validateOnChange' => false,
    'validateOnBlur' => false,
    'options' => [
        'enctype' => 'multipart/form-data',
    ],
]) ?>

<div class="profile-settings__photo">
    
    <?= Html::img($user->photo, [
            'class' => 'img-rounded face',
            'id' => 'photoPreview',
            'alt' => 'user photo']) ?>
    <div class="profile-upload">
        <?= $form->field($user, 'user_photo', [
                    'template' => '<label class="text-center btn-upload" role="button">{input}{label}'])
                ->input('file', ['id' => 'btnLoad', 'class' => 'hidden', 'accept' => 'image/*'])
                ->label('<span class="glyphicon glyphicon-camera"></span>')
        ?>
    </div>
</div>
<div class="profile-settings__info">
        
    <?= $form->field($user, 'user_email', ['template' => '<div class="field"></i>{label}{input}{error}</div>'])
            ->textInput(['class' => 'field-input'])
            ->label($user->getAttributeLabel('user_email'), ['class' => 'field-label']) ?>

    <div class="spam-agree-txt text-center">
        
        <?= $form->field($user, 'user_check_email', ['template' => '{input}{label}'])->checkbox([], false)->label() ?> 

        <div class="save-btn-group">
            <div class="text-center">
                <?= Html::submitButton('Сохранить изменения', ['class' => 'btn-small btn-changes-yes']) ?>
            </div>
        </div>

    </div>
    
</div>
<?php ActiveForm::end(); ?>


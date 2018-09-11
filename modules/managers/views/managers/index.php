<?php
    
    use yii\widgets\ActiveForm;
    use yii\helpers\Html;
    use app\helpers\FormatHelpers;

/*
 * Профиль Администратора
 */    
    
$this->title = "Профиль";
?>
<div class="managers-default-index">
    <h1><?= $this->title ?></h1>
    <?php
        $form = ActiveForm::begin([
            'id' => 'edit-profile-employer'
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
            </div>
        </div>
        <div class="col-md-6">
            <?= $form->field($employer_info, 'employers_surname')
                ->input('text', [
                    'placeHolder' => $employer_info->getAttributeLabel('employers_surname')])
                ->label() ?>
            
            <?= $form->field($employer_info, 'employers_name')
                ->input('text', [
                    'placeHolder' => $employer_info->getAttributeLabel('employers_surname')])
                ->label() ?>
            
            <?= $form->field($employer_info, 'employers_second_name')
                ->input('text', [
                    'placeHolder' => $employer_info->getAttributeLabel('employers_surname')])
                ->label() ?>
            
            <?= $form->field($employer_info, 'employers_department_id')
                ->input('text', [
                    'placeHolder' => $employer_info->getAttributeLabel('employers_department_id')])
                ->label() ?>

            <?php /* = $form->field($employer_info, 'employers_gender')
                ->radioList(\yii\helpers\ArrayHelper::map(app\models\Employers::getGenderArray(), 'id', 'name'))->label(); */ ?>
            
            <?= $form->field($user_model, 'user_mobile')
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

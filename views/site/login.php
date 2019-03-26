<?php

    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\widgets\ActiveForm;

$this->title = 'Вход';
?>

<div class="main-page">
    <div class="main-page__form">
        <div class="main-page__logo">
            <a href="<?= Url::to(['/']) ?>">
                <?= Html::img(Yii::getAlias('@web') . '/images/main/elsa-logo13@2x.png', ['class' => '__logo']) ?>
            </a>
        </div>

        <div class="main-page__title">
            <h2>
                Вход
            </h2>
        </div>
        
        <div class="main-page__form-item">
            <?php
                $form = ActiveForm::begin([
                    'id' => 'login-form',
                    'validateOnChange' => false,
                    'validateOnBlur' => false,
                    'fieldConfig' => [
                        'template' => '<div class="field">{label}{input}{error}</div>',
                        'labelOptions' => ['class' => 'label-registration hidden'],
                    ],
                ])
            ?>
            
                <?= $form->field($model, 'username')
                        ->input('text', [
                            'class' => 'input-registration field-input'])
                        ->label($model->getAttributeLabel('username'), ['class' => 'field-label']) ?>

                <div class="input-group">
                    <?= $form->field($model, 'password')
                            ->input('password', [
                                'class' => 'input-registration field-input pwd_value'])
                            ->label($model->getAttributeLabel('password'), ['class' => 'field-label']) ?>
                    <span class="input-group-btn">
                        <?= Html::button('<i class="glyphicon glyphicon-eye-open"></i>', ['class' => 'btn form__show-password']) ?>
                    </span> 
                </div>

                <div class="login-form__button text-center">
                    <?= Html::submitButton('Вход', [
                            'class' => 'btn blue-btn', 
                            'name' => 'login-button']) ?>
                    <?= Html::a('Отмена', ['/'], ['class' => 'btn btn-link red-border-btn-link']) ?>
                </div>

                <div class="text-center">
                    <a href="<?= Url::to(['site/request-password-reset']) ?>" class="forgot-a">Забыли пароль?</a>        
                </div>


            <?php ActiveForm::end(); ?>            
        </div>
        
        
    </div>
</div>
<?php

    use yii\helpers\Url;
    use yii\helpers\Html;

/* 
 * Форма регистрации
 */
$this->title = 'Customers | Регистрация';
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
                Регистрация
            </h2>
        </div>
        
        <div class="main-page__form-item">
            
            <?php if( Yii::$app->session->hasFlash('success') ): ?>
                <div class="alert alert-register-success alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <?= Yii::$app->session->getFlash('success'); ?>
                </div>
            <?php endif;?>
            <?php if( Yii::$app->session->hasFlash('error') ): ?>
                <div class="alert alert-register-error alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <?= Yii::$app->session->getFlash('error'); ?>
            </div>
            <?php endif;?>
            
            <ul id="steps">
                <li id="stepDesc0" class="<?= $_SESSION['step_one'] == 'success-true' ? 'current' : ''?>">Шаг 1<span>Лицевой счет</span></li>
                <li id="stepDesc1" class="<?= $_SESSION['step_two'] == 'success-true' ? 'current' : ''?>">Шаг 2<span>Пользовательские данные</span></li>
                <li id="stepDesc2" class="<?= $_SESSION['step_three'] == 'success-true' ? 'current' : ''?>">Шаг 3<span>Завершение регистрации</span></li>
            </ul>
            
            <?php if ($_SESSION['step_one'] == 'success-true') : ?>
                <div id="step0">
                    <fieldset>
                        <?= $this->render('form/step_one', ['model_step_one' => $model_step_one]) ?>
                    </fieldset>
                </div>
            <?php endif; ?>

            <?php if ($_SESSION['step_two'] == 'success-true') : ?>
                <div id="step1">
                    <fieldset>
                        <?= $this->render('form/step_two', ['model_step_two' => $model_step_two]) ?>
                    </fieldset>
                </div>
            <?php endif; ?>

            <?php if ($_SESSION['step_three'] == 'success-true') : ?>
                <div id="step2">
                    <fieldset>
                        <?= $this->render('form/step_three', ['model_step_three' => $model_step_three]) ?>
                    </fieldset>
                </div>
            <?php endif; ?>
            
        </div>
        
    </div>
    
</div>
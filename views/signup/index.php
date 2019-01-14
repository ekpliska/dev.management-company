<?php

    use yii\helpers\Url;

/* 
 * Форма регистрации
 */
$this->title = 'Customers | Регистрация';
?>

<a style="display:block" href="<?= Url::to(['/']) ?>">
    <div class="registration-logo"></div>
</a>
<div class="slide-content">
    <h2 class="text-center registration-title">
        Регистрация
    </h2>

    <div class="registration-form-group">
        <ul id="steps">
            <li id="stepDesc0" class="<?= $_SESSION['step_one'] == true ? 'current' : ''?>">Шаг 1<span>Лицевой счет</span></li>
            <li id="stepDesc1" class="<?= isset($_SESSION['step_two']) == true ? 'current' : ''?>">Шаг 2<span>Пользовательские данные</span></li>
            <li id="stepDesc2" class="<?= isset($_SESSION['step_three']) == true ? 'current' : ''?>">Шаг 3<span>Завершение регистрации</span></li>
        </ul>

        <?php if ($_SESSION['step_one'] == true) : ?>
            <div id="step0">
                <fieldset>
                    <?= $this->render('form/step_one', ['model_step_one' => $model_step_one]) ?>
                </fieldset>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['step_two']) == true) : ?>
            <div id="step1">
                <fieldset>
                    <?= $this->render('form/step_two', ['model_step_two' => $model_step_two]) ?>
                </fieldset>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['step_three']) == true) : ?>
            <div id="step2">
                <fieldset>
                    <?= $this->render('form/step_three', ['model_step_three' => $model_step_three]) ?>
                </fieldset>
            </div>
        <?php endif; ?>

    </div>
    
</div>
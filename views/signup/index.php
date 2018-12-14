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
    <div>
        <?php
        var_dump($_SESSION);
        ?>
    </div>

    <div class="registration-form-group">
        <ul id="steps">
            <li id="stepDesc0" class="<?= $_SESSION['count_step'] == 0 ? 'current' : ''?>">Шаг 1<span>Лицевой счет</span></li>
            <li id="stepDesc1" class="<?= $_SESSION['count_step'] == 1 ? 'current' : ''?>">Шаг 2<span>Пользовательские данные</span></li>
            <li id="stepDesc2" class="<?= $_SESSION['count_step'] == 2 ? 'current' : ''?>">Шаг 3<span>Завершение регистрации</span></li>
        </ul>

        <?php if ($_SESSION['count_step'] == 0) : ?>
            <div id="step0">
                <fieldset>
                    <?= $this->render('form/step_one', ['model_step_one' => $model_step_one]) ?>
                </fieldset>
            </div>
        <?php endif; ?>

        <?php if ($_SESSION['count_step'] == 1) : ?>
            <div id="step1">
                <fieldset>
                    <?= $this->render('form/step_two', ['model_step_two' => $model_step_two]) ?>
                </fieldset>
            </div>
        <?php endif; ?>

        <?php if ($_SESSION['count_step'] == 2) : ?>
            <div id="step2">
                <fieldset>
                    <?= $this->render('form/step_three', ['model_step_three' => $model_step_three]) ?>
                </fieldset>
            </div>
        <?php endif; ?>

    </div>
    
</div>
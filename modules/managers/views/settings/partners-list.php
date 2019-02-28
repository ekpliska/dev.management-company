<?php

    use yii\widgets\Breadcrumbs;

/* 
 * Настройки, главная
 */
$this->title = Yii::$app->params['site-name-manager'] .  'Настройки';
$this->params['breadcrumbs'][] = 'Настройки';
?>

<div class="manager-main">
    <?= Breadcrumbs::widget([
            'homeLink' => ['label' => 'ELSA | Администратор', 'url' => ['managers/index']],
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
    ]) ?>
    
    <div class="row settings-page">
        <div class="col-lg-3 col-md-3 col-sm-6 col-md-12">
            <h4 class="title">Управление</h4>
            <?= $this->render('sub-menu/menu') ?>
        </div>
        <div class="col-lg-9 col-md-9 col-sm-6 col-md-12 settings-page__content">
            <?= $this->render('pages/partners-list-info', ['partners' => $partners]) ?>
        </div>
    </div>
    
</div>

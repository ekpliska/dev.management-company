<?php

    use yii\widgets\Breadcrumbs;
    use app\modules\managers\widgets\SubSettings;
    use app\modules\managers\widgets\AlertsShow;

/* 
 * Настройки, FAQ
 */
$this->title = Yii::$app->params['site-name-manager'] .  'Настройки';
$this->params['breadcrumbs'][] = 'Настройки';
?>

<?= AlertsShow::widget() ?>

<div class="manager-main">
    <?= Breadcrumbs::widget([
            'homeLink' => ['label' => 'ELSA | Администратор', 'url' => ['managers/index']],
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
    ]) ?>
    
    <div class="row settings-page">
        <div class="col-lg-3 col-md-3 col-sm-6 col-md-12">
            <h4 class="title">Управление</h4>
            <?= SubSettings::widget() ?>
        </div>
        <div class="col-lg-9 col-md-9 col-sm-6 col-md-12 settings-page__content">
            <?= $this->render('pages/faq-settings-info', ['faq_settings' => $faq_settings]) ?>
        </div>
    </div>
    
</div>

<?= $this->render('form/add-faq-item', ['model' => $model]) ?>
<?php

    use yii\bootstrap\Modal;
    use yii\widgets\Breadcrumbs;
    use app\modules\managers\widgets\ModalWindowsManager;
    use app\modules\managers\widgets\AlertsShow;

/* 
 * Конструктор заявок, главная страница
 */
$this->title = Yii::$app->params['site-name-manager'] .  'Конструктор заявок';
$this->params['breadcrumbs'][] = 'Конструктор заявок';
?>
<div class="manager-main-with-sub-designer">
    
    <?= Breadcrumbs::widget([
            'homeLink' => ['label' => 'ELSA | Администратор', 'url' => ['managers/index']],
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
    ]) ?>
    
    <?= AlertsShow::widget() ?>

    <div id="_list-res">
        <?= $this->render("data/index-{$section}", [
                'section' => $section, 
                'results' => $results
            ]) ?>
    </div>
    
</div>

<?php if ($section == 'requests') : ?>
    <?= $this->render('modal/create-request', ['model_request' => $model_request]) ?>
    <?= $this->render('modal/create-question', [
            'model_question' => $model_question,
            'type_requests' => $results['requests'],
        ]) ?>

<?php
    /* Модальное окно для редактирования вопроса */
    Modal::begin([
        'id' => 'edit-question-modal-form',
        'header' => 'Редактировать вопрос',
        'closeButton' => [
            'class' => 'close modal-close-btn',
        ],
        'clientOptions' => [
            'backdrop' => 'static', 
            'keyboard' => false],
    ]);
?>
<?php Modal::end(); ?>

<?php endif; ?>


<?php if ($section == 'paid-services') : ?>
    <?= $this->render('modal/create-category', ['model_category' => $model_category]) ?>
    <?= $this->render('modal/create-service', [
            'model_service' => $model_service, 
            'categories_list' => $results['categories'],
            'units' => $results['units'],
        ]) ?>

<?php
    /* Модальное окно для редактирования услуги */
    Modal::begin([
        'id' => 'edit-service-modal-form',
        'header' => 'Редактировать услугу',
        'closeButton' => [
            'class' => 'close modal-close-btn',
        ],
        'clientOptions' => [
            'backdrop' => 'static', 
            'keyboard' => false],
    ]);
?>
<?php Modal::end(); ?>

<?php endif; ?>

<?= ModalWindowsManager::widget(['modal_view' => 'designer-confirm-message']) ?>

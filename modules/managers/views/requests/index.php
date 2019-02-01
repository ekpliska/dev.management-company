<?php

    use yii\helpers\Html;
    use yii\widgets\Breadcrumbs;
    use yii\bootstrap\ActiveForm;
    use kartik\date\DatePicker;
    use app\modules\managers\widgets\AlertsShow;
    use app\modules\managers\widgets\ModalWindowsManager;

/* 
 * Завяки, главная
 */
$this->title = Yii::$app->params['site-name-manager'] .  'Заявки';
$this->params['breadcrumbs'][] = 'Заявки';
?>

<div class="container-fluid submenu-manager text-center">
    <ul class="nav navbar-nav navbar-left">
        <li>
            <?php 
                $form = ActiveForm::begin([
                    'id' => 'search-request-form',
                    'action' => ['index'],
                    'method' => 'get',
                    'fieldConfig' => [
                        'template' => '{input}',
                    ],
                    'options' => [
                        'class' => 'form-inline',
                    ],
                ]);
            ?>
            
            <?= $form->field($search_model, 'value')->input('text', ['class' => '_search-input', 'placeHolder' => 'ID заявки'])->label(false) ?>
            
                
            <?= $form->field($search_model, 'requests_type_id')->dropDownList($type_requests, [
                    'prompt' => '[Вид заявки]',
                    'class' => '_dropdown-subpanel']) ?>
                
            <?= $form->field($search_model, 'requests_specialist_id')->dropDownList($specialist_lists, [
                    'prompt' => '[Специалист]',
                    'class' => '_dropdown-subpanel']) ?>
            
            <?= $form->field($search_model, 'date_start')
                    ->widget(DatePicker::className(), [
                        'id' => 'date-start',
                        'language' => 'ru',
                        'options' => [
                            'placeHolder' => 'Выберите дату',
                        ],
                        'type' => DatePicker::TYPE_INPUT,
                        'pluginOptions' => [
                            'autoClose' => true,
                            'format' => 'yyyy-mm-dd',
                        ]])
                    ->label(false) ?>
            
            <?= $form->field($search_model, 'date_finish')
                    ->widget(DatePicker::className(), [
                        'id' => 'date-finish',
                        'language' => 'ru',
                        'options' => [
                            'placeHolder' => 'Выберите дату',
                        ],
                        'type' => DatePicker::TYPE_INPUT,
                        'pluginOptions' => [
                            'autoClose' => true,
                            'format' => 'yyyy-mm-dd',
                        ]])
                    ->label(false) ?>
            
            <?= Html::submitButton('', ['class' => 'btn search-block__button']) ?>
            
            <?php ActiveForm::end(); ?>
        </li>
    </ul>
</div>

<div class="manager-main-with-sub">
    
    <?= Breadcrumbs::widget([
            'homeLink' => ['label' => 'ELSA | Администратор', 'url' => ['managers/index']],
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
    ]) ?>
    
    <?= AlertsShow::widget() ?>
    
    <div id="requests-result">
        <?= $this->render('data/grid_requests', [
                'requests' => $requests
        ]) ?>
    </div>
    
    <?= Html::button('', [
            'class' => 'create-request-btn',
            'data-target' => '#create-new-requests',
            'data-toggle' => 'modal',
        ]) ?>

    <?= $this->render('form/create_request', [
            'model' => $model, 
            'type_requests' => $type_requests,
            'flat' => $flat,
        ]) ?>
</div>

<?= ModalWindowsManager::widget(['modal_view' => 'delete_request_modal']) ?>
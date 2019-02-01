<?php
    
    use yii\widgets\Breadcrumbs;
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
    use app\modules\managers\widgets\AlertsShow;
    use app\modules\managers\widgets\ModalWindowsManager;

/*
 * Администраторы
 */    
    
$this->title = Yii::$app->params['site-name-manager'] .  'Администраторы';
$this->params['breadcrumbs'][] = 'Администраторы';
?>

<div class="container-fluid submenu-manager text-center">
    <ul class="nav navbar-nav navbar-left">
        <li>
            <?php 
                $form = ActiveForm::begin([
                    'id' => 'search-managers-form',
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
            
            <?= $form->field($model, 'name')->input('text', ['class' => '_search-input', 'placeHolder' => 'Фамилия имя отчество'])->label(false) ?>
            
            <div class="form-group">
                
            <?= $form->field($model, 'employee_department_id')->dropDownList($departments, [
                    'prompt' => '[Подразделение]',
                    'class' => '_dropdown-subpanel']) ?>
                
            <?= $form->field($model, 'employee_posts_id')->dropDownList($posts, [
                    'prompt' => '[Должность]',
                    'class' => '_dropdown-subpanel']) ?>
                
            </div>
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
    
    <?= $this->render('data/grid', ['manager_list' => $manager_list]) ?>
    
    <?= Html::a('', ['employee-form/index', 'new_employee' => 'administrator'], ['class' => 'create-request-btn']) ?>
    
</div>

<?= ModalWindowsManager::widget(['modal_view' => 'delete_employee']) ?>
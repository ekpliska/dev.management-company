<?php

    use app\modules\managers\widgets\AlertsShow;
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
    use yii\widgets\Breadcrumbs;
    use app\modules\managers\widgets\ModalWindowsManager;

/* 
 * Специалисты
 */
$this->title = Yii::$app->params['site-name-manager'] .  'Специалисты';
$this->params['breadcrumbs'][] = 'Специалисты';
?>

<div class="container-fluid submenu-manager text-center">
    <ul class="nav navbar-nav navbar-left">
        <li>
            <?php 
                $form = ActiveForm::begin([
                    'id' => 'search-dispatchers-form',
                    'action' => ['specialists'],
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
    
    <?= $this->render('data/grid_specialists', ['specialists' => $specialists]) ?>
    
    <?= Html::a('', ['employee-form/index', 'new_employee' => 'specialist'], ['class' => 'create-request-btn']) ?>
    
</div>

<?= ModalWindowsManager::widget(['modal_view' => 'delete_employee']) ?>
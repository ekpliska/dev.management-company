<?php

    use yii\widgets\Breadcrumbs;
    use app\modules\managers\widgets\SubSettings;

/* 
 * Настройки, Подразделения, Должности
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
            <?= SubSettings::widget() ?>
        </div>
        <div class="col-lg-9 col-md-9 col-sm-6 col-md-12 settings-page__content text-center">
            <?= $this->render('pages/service-duty-info', [
                    'departments' => $departments,
                    'posts' => $posts,
                    'department_lists' => $department_lists,
                ]) ?>
        </div>
    </div>
    
</div>

<?= $this->render('form/add-department', ['department_model' => $department_model]) ?>
<?= $this->render('form/add-post', ['post_model' => $post_model, 'department_lists' => $department_lists]) ?>
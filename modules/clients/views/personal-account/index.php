<?php
    use yii\widgets\ActiveForm;
    use yii\helpers\Html;
    use yii\helpers\ArrayHelper;
    use yii\widgets\Pjax;
    use yii\widgets\ListView;
    use yii\helpers\Url;
/* 
 * Лицевой счет / Общая информация
 */
$this->title = 'Общая информация';
?>
<div class="clients-default-index">
    <h1><?= $this->title ?></h1>

        <?php if (Yii::$app->session->hasFlash('form')) : ?>
            <div class="alert alert-info" role="alert">
                <strong>
                    <?= Yii::$app->session->getFlash('form', false); ?>
                </strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>                    
            </div>
        <?php endif; ?>

        <?php if (Yii::$app->session->hasFlash('error')) : ?>
            <div class="alert alert-error" role="alert">
                <strong>
                    <?= Yii::$app->session->getFlash('error', false); ?>
                </strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>                
            </div>
        <?php endif; ?>         
            
    
    
    <div class="col-md-6">
        <?php 
            $form_filter = ActiveForm::begin([
                'id' => 'filter-form-account',
                'method' => 'GET',
                'options' => [
                    'class' => 'form-inline',
                    'data-pjax' => true,
                ],
            ]); 
        ?>
        
            <?= $form_filter->field($_filter, '_value')
                ->dropDownList($account_all, [
                    'onchange' => '$.pjax.reload({container: "#list-account", url: "'.Url::to(['personal-account/list']).'", data: {id: $(this).val()}});'])
                ->label('Лицевой счет')
                ?>
        
        <?php ActiveForm::end(); ?> 
    </div>
    
    <div class="col-md-6 text-right">
        <?= Html::a('Добавить лицевой счет', Url::to(['personal-account/show-add-form']), ['class' => 'btn btn-primary']) ?>
    </div>
    <div class="clearfix"></div>    
    
    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading"><strong>Информация по лицевому счету</strong></div>
            <div class="panel-body">
                <?php Pjax::begin(['enablePushState' => false, 'id' => 'list-account']); ?>
            
                    <?= ListView::widget([
                        'dataProvider' => $dataProvider,
                        'itemView' => 'list',
                        // 'layout' => "{pager}\n{summary}\n{items}\n{pager}",
                    ]) ?>
            
                <?php Pjax::end(); ?>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="text-left">
                    <strong>Информаци об оплате</strong>                    
                </div>
            </div>            
            <div class="panel-body">
                test
            </div>
        </div>
    </div>    
</div>

<div id="filter_id_test">
    
</div>
<?php
    use yii\helpers\Html;
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
        
        <?= Html::dropDownList('_list-account-all', null, $account_all, ['class' => 'form-control _list-account-all']) ?>
        
        <br />
    </div>
    
    <div class="col-md-6 text-right">
        <?= Html::a('Добавить лицевой счет', Url::to(['personal-account/show-add-form']), ['class' => 'btn btn-primary']) ?>
    </div>
    <div class="clearfix"></div>    
    
    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading"><strong>Информация по лицевому счету</strong></div>
            <div class="panel-body">
                <?= $this->render('list', ['model' => $account_info]); ?>
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
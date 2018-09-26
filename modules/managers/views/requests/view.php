<?php

    use yii\helpers\Html;
    use app\modules\managers\widgets\AlertsShow;
    use app\helpers\FormatHelpers;

/* 
 * Просмотр и редактирование заявки
 */
$this->title = 'Заявка №' . $request['applications_number'];
?>
<div class="managers-default-index">
    <h1><?= $this->title ?></h1>
    <hr />
    
    <?= AlertsShow::widget() ?>
    
    <div class="col-md-7">
        <div class="panel panel-default">
            <div class="panel-heading">
                <?= $request['is_accept'] ? 'Заявка принята' : 'Заявка не принята' ?>
            </div>
            <div class="panel-body">
                
                <div class="col-md-12">
                    <?= $request['type_requests_name'] ?>
                    <hr />
                </div>
                
                <div class="col-md-4">
                    <span class="label label-warning">
                        <?= $request['requests_ident'] ?>
                    </span>
                </div>
                <div class="col-md-8">
                    <span class="label label-primary">
                        <?= FormatHelpers::statusName($request['status']) ?> 
                    </span>
                    &nbsp;&nbsp;&nbsp;
                    <?= FormatHelpers::formatDate($request['created_at']) ?>
                    <hr />
                </div>
                
                <div class="col-md-12">
                    <?= $request['requests_comment'] ?>
                    <hr />
                </div>
                
                <div class="col-md-6">
                    <?= FormatHelpers::formatFullAdress(
                            $request['houses_town'], 
                            $request['houses_street'], 
                            $request['houses_number_house'], 
                            $request['houses_floor'], 
                            $request['houses_flat']) ?>
                </div>
                <div class="col-md-6">
                    <?= $request['requests_phone'] ?>
                </div>
                
                <div class="clearfix"></div>
                <hr />
                <div class="col-md-12 text-center">
                    <?= Html::button('Назначить исполнителя', ['class' => 'btn btn-primary']) ?>
                    <?= Html::button('Отклонить', ['class' => 'btn btn-danger']) ?>
                </div>
                
            </div>
        </div>        
    </div>
    <div class="col-md-5">
        <div class="panel panel-default">
            <div class="panel-heading">Комменатрии к заявке</div>
            <div class="panel-body">
                <?= $this->render('comments/view', [
                    'model' => $model_comment,
                    'comments_find' => $comments_find]) ?>
            </div>
        </div>
    </div>
    
    <div class="clearfix"></div>
    
</div>

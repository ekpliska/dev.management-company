<?php

    use yii\helpers\Html;
    use app\modules\managers\widgets\AlertsShow;
    use app\helpers\FormatHelpers;
    use app\helpers\FormatFullNameUser;
    use app\modules\managers\widgets\SwitchStatusRequest;

/* 
 * Просмотр и редактирование заявки
 */
$this->title = 'Заявка №' . $request['requests_ident'];
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
                    
                    <?= SwitchStatusRequest::widget([
                            'status' => $request['status'],
                            'request_id' => $request['requests_id']]) ?>
                    
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
                    <br />
                    <?= FormatFullNameUser::fullNameByPhone($request['requests_phone']) ?>
                </div>
                
                <div class="clearfix"></div>
                <hr />
                
                <div class="col-md-12 text-center">
                    <div class="col-md-4">
                        <?= FormatFullNameUser::fullNameEmployer($request['requests_dispatcher_id'], true) ?>
                        <br/>
                        <?= Html::button('Назначить диспетчера', ['class' => 'btn btn-default']) ?>
                    </div>
                    <div class="col-md-4">
                        <?= FormatFullNameUser::fullNameEmployer($request['requests_specialist_id']) ?>
                        <br/>
                        <?= Html::button('Назначить специалиста', ['class' => 'btn btn-default']) ?>
                    </div>
                    <div class="col-md-4">
                        <br />
                        <?= Html::button('Отклонить', ['class' => 'btn btn-danger reject-request']) ?>
                    </div>
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

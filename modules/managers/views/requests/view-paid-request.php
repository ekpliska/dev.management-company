<?php

    use yii\helpers\Html;
    use app\modules\managers\widgets\AlertsShow;
    use app\helpers\FormatHelpers;
    use app\helpers\FormatFullNameUser;
    use app\modules\managers\widgets\SwitchStatusRequest;
    use app\modules\managers\widgets\AddEmployee;

/* 
 * Просмотр и редактирование заявки на платную услугу
 */
$this->title = 'Заявка №' . $paid_request['services_number'];
?>
<div class="managers-default-index">
    <h1><?= $this->title ?></h1>
    <hr />
    
    <?= AlertsShow::widget() ?>
    
    <div class="col-md-7">
        <div class="panel panel-default">
            <div class="panel-heading">
                <?= $paid_request['category_name'] ?>
            </div>
            <div class="panel-body">
                
                <div class="col-md-12">
                    <?= $paid_request['services_name'] ?>
                    <br />
                    <?= FormatHelpers::formatDate($paid_request['date_create']) ?> 
                    <div id="star" data-request="<?= $paid_request['services_id'] ?>" data-score-reguest="<?= $paid_request['services_grade'] ?>"></div>
                    <hr />
                </div>
                
                <div class="col-md-4">
                    <span class="label label-warning">
                        <?= $paid_request['services_number'] ?>
                    </span>
                </div>
                <div class="col-md-8">
                    
                    <?= SwitchStatusRequest::widget([
                            'status' => $paid_request['status'],
                            'request_id' => $paid_request['services_id']]) ?>
                    
                    <?= FormatHelpers::formatDate($paid_request['updated_at']) ?>   
                    <hr />                 
                </div>
                
                <div class="col-md-12">
                    <?= $paid_request['services_comment'] ?>
                    <hr />
                </div>
                
                <div class="col-md-6">
                    <?= FormatHelpers::formatFullAdress(
                            $paid_request['houses_town'], 
                            $paid_request['houses_street'], 
                            $paid_request['houses_number_house'], 
                            $paid_request['houses_floor'], 
                            $paid_request['houses_flat']) ?>
                </div>
                <div class="col-md-6">
                    <?= $paid_request['services_phone'] ?>
                    <br />
                    <?= FormatFullNameUser::fullNameByPhone($paid_request['services_phone']) ?>
                </div>
                
                <div class="clearfix"></div>
                <hr />
                
                <div class="col-md-12 text-center">
                    <div class="col-md-4">
                        <div id="dispatcher-name">
                            <?= FormatFullNameUser::fullNameEmployer($paid_request['services_dispatcher_id'], true, true) ?>
                        </div>
                        <br/>
                        <?= Html::button('Назначить диспетчера', [
                            'class' => 'btn btn-default btn-dispatcher',
                            'data-employee' => $paid_request['services_dispatcher_id'],
                            'data-target' => '#add-dispatcher-modal',
                            'data-toggle' => 'modal']) ?>
                    </div>
                    <div class="col-md-4">
                        <div id="specialist-name">
                            <?= FormatFullNameUser::fullNameEmployer($paid_request['services_specialist_id'], false, true) ?>
                        </div>
                        <br/>
                        <?= Html::button('Назначить специалиста', [
                            'class' => 'btn btn-default',
                            'data-employee' => $paid_request['services_specialist_id'],
                            'data-target' => '#add-specialist-modal',
                            'data-toggle' => 'modal']) ?>
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
                <?php /* = $this->render('comments/view', [
                    'model' => $model_comment,
                    'comments_find' => $comments_find]) */ ?>
            </div>
        </div>
    </div>
    
    <div class="clearfix"></div>
    <?= AddEmployee::widget() ?>
</div>


<?php
$grade = $paid_request['services_grade'] ? $paid_request['services_grade'] : 0; 
$this->registerJs("
$('div#star').raty({
    score: " . $grade . ",
    readOnly: true,
});    
")
?> 
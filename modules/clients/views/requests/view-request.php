<?php

    use yii\helpers\Html;
    use app\helpers\FormatHelpers;    
    use app\modules\clients\widgets\RatingRequest;
    use app\helpers\StatusHelpers;
    use app\models\StatusRequest;

/* 
 * Детали заявки
 */
$this->title = 'Детали заявки';
?>


<div class="requests-view row">
    <div class="col-md-12 requests-view_title">
        <i class="glyphicon glyphicon-ok <?= $request_info['is_accept'] ? 'check' : 'uncheck' ?>"></i>&nbsp;&nbsp;Заявка принята
    </div>
    

    <div class="col-md-7 requests-view_body">
        <h4>
            <?= $request_info['type_requests_name'] ?>
        </h4>
        <p class="date_requests">
            <?= FormatHelpers::formatDate($request_info['created_at'], true, 0, false) ?>
        </p>
        <?php if ($request_info['status'] == StatusRequest::STATUS_CLOSE) : ?>        
            <div class="req-rate-star">
                <div class="starrr" id="star1">

                    <?= RatingRequest::widget([
                        '_status' => $request_info['status'], 
                        '_request_id' => $request_info['requests_id'],
                        '_score' => $request_info['requests_grade']]) ?>

                </div>
            </div>
        <?php endif; ?>
        <span class="badge request-ident">
            <?= $request_info['requests_ident'] ?>            
        </span>
        <?= StatusHelpers::requestStatusPage($request_info['status'], $request_info['updated_at']) ?>
        <p class="request_text">
            <?= $request_info['requests_comment'] ?>
        </p>
        
        <div class="client_info">
            <div class="col-lg-6 col-sm-6 col-md-6 text-left">
                <i class="glyphicon glyphicon-home"></i> 
                <?= Yii::$app->userProfile->getFullAdress($this->context->_choosing)?>
            </div>
            <div class="col-lg-6 col-sm-6 col-md-6 text-left">
                <i class="glyphicon glyphicon-phone"></i>&nbsp;&nbsp;<?= $request_info['requests_phone'] ?>
            </div>
        </div>
        
        <div class="request-body-rate">
            <?php if ($request_info['status'] == StatusRequest::STATUS_CLOSE) : ?>
                <?= Html::button('Оценить', ['class' => 'btn blue-outline-btn']) ?>
            <?php endif; ?> 
        </div>
    </div>
    
    <div class="col-md-5 requests-view_chat">
        <?= $this->render('form/_comment', [
            'model' => $comments, 
            'comments_find' => $comments_find, 
            'request_id' => $request_info['requests_id']
        ]); ?>        
        
    </div>
</div>
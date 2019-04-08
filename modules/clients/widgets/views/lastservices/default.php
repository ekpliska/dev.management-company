<?php

    use yii\helpers\Html;
    use yii\bootstrap\Modal;

/* 
 * Услуги Для главной страницы
 */
?>
<?php if (isset($services_lists) && count($services_lists) > 0) : ?>
<h1>
    Услуги
</h1>
<?php foreach ($services_lists as $key => $service) : ?>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
        <div class="service-item">
            <div class="service-item__image">
                <?= Html::img($service->getImage(), ['class' => '', 'alt' => $service->service_name]) ?>
            </div>
            <div class="service-item__content">
                <h1>
                    <?= $service->service_name ?>                    
                </h1>
                <p class="cost-service">
                    <?= "{$service->service_price} &#8381;" ?>
                </p>
                <p>
                    <?= $service->service_description ?>
                </p>
                <div class="service-item__btn text-center">
                    <?= Html::a('Заказать',
                            ['paid-services/create-paid-request', 
                                'category' => $service->service_category_id, 
                                'service' => $service->service_id], 
                            ['class' => 'order-to-service new-rec']) ?>                    
                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>
<?php endif; ?>

<?php
    Modal::begin([
        'id' => 'add-paid-request-modal',
        'header' => 'Заявка на платную услугу',
        'closeButton' => [
            'class' => 'close modal-close-btn btn__paid_service_close',
        ],
        'clientOptions' => [
            'backdrop' => 'static',
            'keyboard' => false,
        ],
    ]);
?>
    
<?php Modal::end(); ?>
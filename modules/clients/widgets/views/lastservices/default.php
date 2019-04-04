<?php

    use yii\helpers\Html;

/* 
 * Услуги Для главной страницы
 */
?>
<?php if (isset($services_lists) && count($services_lists) > 0) : ?>
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
                    <?= Html::a('Заказать',
                            ['create-paid-request', 'category' => $service['category']['category_id'], 'service' => $service['service_id']], 
                            ['class' => 'btn-link card-link-blue new-rec']) ?>                    
            </div>
        </div>
    </div>
<?php endforeach; ?>
<?php endif; ?>
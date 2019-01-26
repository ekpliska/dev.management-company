<?php
    use yii\helpers\Html;

    
/*
 * Список платных услуг
 */    
?>
<div id="services-list">
    <?php if ($pay_services): ?>
        <?php foreach ($pay_services as $key => $service) : ?>
            <div class="col-md-4">
                <div class="service-card">
                    <div class="service-card_title">
                        <?= $service['category']['category_name'] ?>
                    </div>
                    <?= Html::img($service['service_image'], ['class' => 'service-card_image', 'alt' => 'service-image']) ?>
                    <div class="service-card_title">
                        <?= $service['service_name'] ?>
                    </div>
                    <div class="service-card__body">
                        <!--  ограничение на 250 символов -->
                        <p>
                            <?= $service['service_description'] ?>
                        </p>
                    </div>
                    <div class="service-card__btn">
                        <span class="cost_service"><?= $service['service_price'] ?> &#8381;</span>
                        <?php /*= Html::button('Заказать', [
                                'class' => 'btn blue-outline-btn new-rec', 
                                'data-service-cat' => $service['category']['category_id'],
                                'data-service' => $service['service_id']]) */ ?>
                        
                        <?= Html::a('Заказать', 
                                ['create-paid-request', 'category' => $service['category']['category_id'], 'service' => $service['service_id']], 
                                ['class' => 'btn add-service-req new-rec']) ?>
                    </div>                
                </div>
            </div>

        <?php endforeach; ?>
    <?php else : ?>
        <div class="notice notice-info">
            <strong>Услуги</strong> по заданной категории услуг не найдено.
        </div>
    <?php endif; ?>
</div>
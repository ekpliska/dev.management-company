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
                    <?= Html::img($service['services_image'], ['class' => 'service-card_image', 'alt' => $service['services_name']]) ?>
                    <div class="service-card_title">
                        <?= $service['services_name'] ?>
                    </div>
                    <div class="service-card__body">
                        <!--  ограничение на 250 символов -->
                        <p>
                            <?= $service['services_description'] ?>
                        </p>
                    </div>
                    <div class="service-card__btn">
                        <span class="cost_service"><?= $service['services_cost'] ?> &#8381;</span>
                        <?= Html::button('Заказать', [
                                'class' => 'btn blue-outline-btn new-rec', 
                                'data-service-cat' => $service['category']['category_id'],
                                'data-service' => $service['services_id']]) ?>
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
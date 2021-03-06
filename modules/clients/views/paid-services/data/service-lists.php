<?php

    use yii\helpers\Html;
    use app\helpers\FormatHelpers;
    
/*
 * Список платных услуг
 */    
?>
<div id="services-list">
    <?php if ($pay_services): ?>
        <?php foreach ($pay_services as $key => $service) : ?>
            <div class="col-lg-4 col-md-6 col-sm-6 col-xs-6">
                <div class="service-card">
                    <div class="service-card_title">
                        <?= $service['category']['category_name'] ?>
                    </div>
                    <div class="service-card_preview">
                        <?php if (!empty($service['service_image'])) : ?>
                            <?= Html::img($service['service_image'], ['class' => 'service-card_image', 'alt' => 'service-image']) ?>
                        <?php else: ?>
                            <?= Html::img(Yii::getAlias('@web') . '/images/not_found.png', ['class' => 'service-card_image', 'alt' => 'service-image']) ?>
                        <?php endif; ?>
                    </div>
                    <div class="service-card_title">
                        <?= $service['service_name'] ?>
                    </div>
                    <div class="service-card__body">
                        <!--  ограничение на 250 символов -->
                        <p>
                            <?= $service['service_description'] ? 
                                    FormatHelpers::shortTextNews($service['service_description'], 21) : 'Описание услуги отсутствует' ?>
                        </p>
                    </div>
                    <div class="service-card__btn">
                        <span class="cost_service"><?= $service['service_price'] ? "{$service['service_price']}&#8381;" : '' ?></span>
                        <?= Html::a('Заказать', 
                                ['create-paid-request', 'category' => $service['category']['category_id'], 'service' => $service['service_id']], 
                                ['class' => 'card-link-blue new-rec']) ?>
                    </div>                
                </div>
            </div>

        <?php endforeach; ?>
    <?php else : ?>
        <div class="notice info">
            <p>По заданной категории услуг не найдено.</p>
        </div>
    <?php endif; ?>
</div>
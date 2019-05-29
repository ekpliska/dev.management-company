<?php

    use yii\helpers\Html;

/* 
 * Новые услуги
 */
?>

<div class="__title">
    <h5>
        Новые услуги
        <?= Html::a('Платные услуги', ['designer-requests/index', 'section' => 'paid-services'], ['class' => 'active_block__link pull-right']) ?>
    </h5>
</div>
<div class="__content">
    <?php if (isset($new_services) && $new_services) : ?>
        <div class="active_block__content">
                <div class="active_block__content">
                <?php foreach ($new_services as $key => $service) : ?>
                    <div class="active_block__item">
                        <div class="active_block__item-image">
                            <?= Html::img(Yii::getAlias('@web') . $service->getImage()) ?>
                        </div>
                        <div class="active_block__item-section">
                            <p class="service-name" class=""><?= $service->service_name ?></p>
                            <p class="category-name"><?= $service->category->category_name ?></p>
                            <div class="active_block__info">
                                <span>Цена: </span>
                                <span class="active_block__span-info">
                                    <?= $service->service_price ? $service->service_price : 'Не установлена' ?>
                                </span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
                </div>                
        </div>
    <?php else: ?>
         <p>
            Информация не доступна.
        </p>
        <?= Html::a('Платные услуги', ['managers/designer-requests', 'section' => 'paid-services'], ['class' => 'active_block__link']) ?>
    <?php endif; ?>
</div>
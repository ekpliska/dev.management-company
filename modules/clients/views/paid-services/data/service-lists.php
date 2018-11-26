<?php
    use yii\helpers\Html;

    
/*
 * Список платных услуг
 */    
?>

<?php if ($pay_services): ?>
    <?php foreach ($pay_services as $key => $service) : ?>

        <div class="col-4">
            <div class="card services-card-preview box-shadow paid-service-list">
                <div class="services-card-preview-executor-container">
                    <h5 class="services-card-preview-executor">
                        <?= $service['category']['category_name'] ?>
                    </h5> 
                </div>
                <?= Html::img($service['services_image'], ['class' => 'card-img-top services-card-img-top-preview', 'alt' => $service['services_name']]) ?>
                <h5 class="services-card-preview-h">
                    <?= $service['services_name'] ?>
                </h5>
                <div class="card-body m-0 p-0 services-card-preview-body">
                    <!--  ограничение на 250 символов -->
                    <p class="card-text services-card-preview-text mt-0">
                        <?= $service['services_description'] ?>
                    </p>
                    <div class="services-btn-container">
                        <span class="cost_service"><?= $service['services_cost'] ?> &#8381;</span>
                        <?= Html::button('Заказать', [
                                'class' => 'btn blue-outline-btn btn-add-servic mx-auto new-rec', 
                                'data-service-cat' => $service['category']['category_id'],
                                'data-service' => $service['services_id']]) ?>
                    </div>
                    <div class="d-flex justify-content-around align-items-center">
                    </div>
                </div>
            </div>
        </div>
    
    <?php endforeach; ?>
<?php else : ?>
    <div class="notice notice-info">
        <strong>Услуги</strong> по заданной категории услуг не найдено.
    </div>
<?php endif; ?>
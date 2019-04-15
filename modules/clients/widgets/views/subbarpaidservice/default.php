<?php

    use yii\helpers\Html;

/* 
 * Вид дополнительного навигационного меню для страниц 
 *      Платные услуги
 */
?>

<div class="row navbar_paid-request">
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <div class="category-select">
            <p class="category-select-label">
                Категории услуг:
            </p>
            <?= Html::dropDownList('category_list', 1, $category_list, [
                    'placeholder' => reset($category_list),
                    'id' => 'sources-services',
                    'class' => 'custom-select-services sources-services']) 
            ?>
        </div>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-left">
        <?= Html::a('История', ['paid-services/order-services'], ['class' => 'btn-history']) ?>
    </div>
</div>
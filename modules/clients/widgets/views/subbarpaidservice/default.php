<?php

    use yii\helpers\Html;

/* 
 * Вид дополнительного навигационного меню для страниц 
 *      Платные услуги
 *      История платных услуг
 */
?>

<?php if (Yii::$app->controller->id == 'paid-services' && Yii::$app->controller->action->id == 'index') : ?>
    <nav class="navbar services-nav navbar-dark justify-content-between navbar-expand-sm p-0 carousel-item d-block">
        <ul class="nav  mx-auto text-center justify-content-center">
            <li class="nav-item">
                <div class="col-12 text-left material category-select-block">
                    <div class="category-select mx-auto">
                        <?= Html::dropDownList('category_list', -1, $category_list, [
                                'prompt' => 'Все категории', 'class' => '']) ?>
                    </div>
                </div>
            </li>
            <li class="nav-item ml-auto">
                <div class="history-btn-block">
                    <?= Html::a('История', ['paid-services/order-services'], ['class' => 'btn blue-btn white-outline']) ?>
                </div>
            </li>
            <li class="nav-item">

            </li>
        </ul>
    </nav>

<?php elseif(Yii::$app->controller->id == 'paid-services' && Yii::$app->controller->action->id == 'order-services') : ?>

    <div class="container-fluid block-searsh">
        <div class="field-searsh">
            <?= Html::input('text', 'services-search-input', null, ['placeHolder' => 'Поиск']) ?>
        </div>
    </div>

<?php endif; ?>

<?php

    use yii\helpers\Html;
    use yii\widgets\ActiveForm;

/* 
 * Вид дополнительного навигационного меню для страниц 
 *      Платные услуги
 *      История платных услуг
 */
?>

<?php if (Yii::$app->controller->id == 'paid-services' && Yii::$app->controller->action->id == 'index') : ?>
<div class="row navbar_paid-request">
    <div class="col-lg-6">
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
    <div class="col-lg-6 text-left">
        <?= Html::a('История', ['paid-services/order-services'], ['class' => 'btn-history']) ?>
    </div>
</div>

    <?php elseif(Yii::$app->controller->id == 'paid-services' && Yii::$app->controller->action->id == 'order-services') : ?>
    <div class="container-fluid navbar_paid-request text-center menu_sub-bar">
        <?php
            $form = ActiveForm::begin([
                'id' => 'search-form',
                'fieldConfig' => [
                    'template' => '{label}{input}',
                ],
            ]);
        ?>
            <?= $form->field($_search, '_input')
                    ->input('text', [
                        'placeHolder' => 'Поиск',
                        'id' => '_search-input',
                        'class' => 'search-block__input-dark'])
                    ->label(false) ?>

        <?php ActiveForm::end(); ?>
    </div>
<?php endif; ?>

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
    <div class="container-fluid navbar_paid-request text-center">
        <ul class="nav navbar-nav navbar-left">
            <li>
                <div class="category-select">
                    <?= Html::dropDownList('category_list', 1, $category_list, [
                            'placeholder' => reset($category_list),
                            'id' => 'sources-services',
                            'class' => 'custom-select-services sources-services']) 
                    ?>
                </div>
            </li>
        </ul>
        <ul class="nav navbar-nav navbar-right">
            <li>
                <?= Html::a('История', ['paid-services/order-services'], ['class' => 'btn-history']) ?>
            </li>
        </ul>
     </div>

<?php elseif(Yii::$app->controller->id == 'paid-services' && Yii::$app->controller->action->id == 'order-services') : ?>
    <div class="container-fluid navbar_paid-request text-center">
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
                        'placeHolder' => $_search->getAttributeLabel('_input'),
                        'id' => '_search-input'])
                    ->label(false) ?>

        <?php ActiveForm::end(); ?>
    </div>
<?php endif; ?>

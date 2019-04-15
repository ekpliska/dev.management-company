<?php

    use yii\helpers\Html;
    use app\modules\clients\widgets\ModalWindows;

/* 
 * Вкладка "Арендатор"
 */
?>
<div class="rent-tab">
    <?php if (!$is_rent) : // Если у текущего ЛС нет арендатора ?>
    <div class="rent-tab__btn">
        <?= Html::button('Добавить арендатора', [
                'class' => 'add-rent-btn',
                'data-toggle' => 'modal',
                'data-target' => '#add-rent-modal'
            ]) ?>
        <?= $this->render('../form/create-rent', ['add_rent' => $add_rent]) ?>
    </div>
    <?php else: // Если у текущего ЛС есть арендатор ?>
        <?= $this->render('../data/rent-view', ['model_rent' => $rent_info]) ?>
        <?= ModalWindows::widget(['modal_view' => 'changes_rent']) ?>
    <?php endif; ?>
</div>
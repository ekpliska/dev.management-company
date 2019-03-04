<?php

    use yii\helpers\Html;
    use app\helpers\FormatHelpers;

/* 
 * Просмотр квартир
 */
?>

<h4 class="title">
    Квартиры
    <span class="span-count"><?= count($flats) ?></span>
</h4>

<?php if (isset($flats) && $flats) : ?>
    <?php 
        foreach ($flats as $key => $flat) : 
        // Если баланс лицевого счета собственика отрицателен, то Собственник - должник
        $debtor = $flat['account']['account_balance'] < 0 ? true : false;
    ?>
    <div class="flats-info">
        <h3 class="<?= $debtor ? 'title-debtor' : 'title' ?>">
            <?= "Квартира {$flat['flats_number']}, подъезд {$flat['flats_porch']}" ?>

            <?php if (Yii::$app->user->can('EstatesEdit')) : ?>
                <?= Html::button('', ['id' => 'add-note', 'data-flat' => $flat['flats_id']]) ?>
            <?php endif; ?>

        </h3>
        
        <div class="flats-info__info">
            <div class="flats-info__info_image">
                <?= FormatHelpers::formatUserPhoto($flat['user_photo']) ?>
            </div>
            <div class="flats-info__info_content">
                <span>Собственник</span>
                <p class="client-name">
                    <?= FormatHelpers::formatFullUserName($flat['clients_surname'], $flat['clients_name'], $flat['clients_second_name'], true) ?>
                    
                    <?php if (Yii::$app->user->can('EstatesEdit') && $flat['is_debtor']) : ?>
                        <label class="switch-rule pull-right">
                            <?= Html::checkbox($flat_status, $flat['is_debtor'], [
                                    'id' => "check_status__flat-{$flat['flats_id']}",
                                    'data-flat' => $flat['flats_id']]) ?>
                            <span class="slider round"></span>
                        </label>
                    <?php endif; ?>
                    
                </p>                

                <?php if (isset($flats[$key]['note']) && $flats[$key]['note']) : ?>
                    <ul class="notes-flats-list" id="note_flat__tr-<?= $flat['flats_id'] ?>">
                        <li class="first-title"><span>Примечание</span></li>
                        
                        <?php foreach ($flats[$key]['note'] as $note) : ?>
                        <li>
                            <?= $note['notes_name'] ?>
                            <?php if (Yii::$app->user->can('EstatesEdit')) : ?>
                                <span class="close flat_note__delete" data-note="<?= $note['notes_id'] ?>">&#x2715;</span>
                            <?php endif; ?>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>

            </div>
        </div>
    </div>
    <?php endforeach; ?>
<?php else: ?>
<div class="notice info">
    <p>В указанном доме квартиры не найдены</p>
</div>
<?php endif; ?>
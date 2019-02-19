<?php

    use yii\helpers\Html;
    use yii\helpers\Url;
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
        </h3>
        
        <div class="flats-info__info">
            <div class="flats-info__info_image">
                <a href="<?= Url::to([
                    'clients/view-client', 
                    'client_id' => $flat['account']['client']['clients_id'], 
                    'account_number' => $flat['account']['account_number']]) ?>">
                    
                    <?= FormatHelpers::formatUserPhoto($flat['account']['client']['user']['user_photo']) ?>
                </a>
            </div>
            <div class="flats-info__info_content">
                <span>Собственник</span>
                <p class="client-name">
                    <?= FormatHelpers::formatFullUserName(
                            $flat['account']['client']['clients_surname'], 
                            $flat['account']['client']['clients_name'], 
                            $flat['account']['client']['clients_second_name'], true) ?>
                </p>                

                <?php if (isset($flats[$key]['note']) && $flats[$key]['note']) : ?>
                    <ul class="notes-flats-list" id="note_flat__tr-<?= $flat['flats_id'] ?>">
                        <li class="first-title"><span>Примечание</span></li>
                        <?php foreach ($flats[$key]['note'] as $note) : ?>
                        <li>
                            <?= $note['notes_name'] ?>
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
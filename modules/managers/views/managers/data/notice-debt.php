<?php

    use yii\helpers\Html;

/* 
 * Новые услуги
 */
?>

<div class="__title">
    <h5>
        Задолжности
    </h5>
</div>
<div class="__content">
    <?php if (isset($notice_debt) && $notice_debt) : ?>
        <div class="active_block__content">
            <?php foreach ($notice_debt as $key => $notice) : ?>
                <div class="active_block__item">
                    <div style="width: 100%;">
                        <?= $notice->notes_name ?>
                        <div class="active_block__item-section">
                            <div class="active_block__info">
                                <span>Лицевой счет: </span>
                                <span class="active_block__span-info">
                                    <?= $notice->flat->account->account_number ?>
                                </span>
                                <br />
                                <span>Собсвенник: </span>
                                <span class="active_block__span-info">
                                    <?= $notice->flat->account->client->fullName ?>
                                </span>
                            </div>
                        </div>
                    </div>
                    <?= Html::a('Профиль', 
                            ['clients/view-client', 'client_id' => $notice->flat->account->client->clients_id, 'account_number' => $notice->flat->account->account_number], 
                            ['class' => 'new-user-block__debt']) ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
         <p>
            Собсвенники с задолжностью не найдены.
        </p>
    <?php endif; ?>
</div>
<?php

    use yii\helpers\Html;

/* 
 * Футер
 */
?>

<?php if (!empty($organizations_info)) : ?>
<div class="container">
    <div class="row">
        <div class="col-xs-12 col-sm-8 col-md-4">
            <div class="footer__dispatcher">
                <h3 class="distapcher-phone">
                    <a href="<?= "tel:{$organizations_info['dispatcher_phone']}" ?>" class="distapcher-phone">
                        <?= $organizations_info['dispatcher_phone'] ?>
                    </a>
                </h3>
                <p>Круглосуточная диспетчерская служба</p>
                <p>Звонок бесплатный</p>
            </div>
            <div class="footer__help-block">
                <h3 class="footer_title">Нужна помощь?</h3>
                <?= Html::a('Ответы на часто задаваемые вопросы', ['/'], ['class' => 'footer_hepl-link']) ?>
            </div>
        </div>
        <div class="col-xs-12 col-sm-8 col-md-4">
            <div class="footer__adress">
                <h3 class="footer_title">Контакты</h3>
                <h4>Адрес:</h4>
                <p>
                    <?= "{$organizations_info['postcode']}, Россия, {$organizations_info['town']}" ?>
                </p>
                <p>
                    <?= "{$organizations_info['street']}, {$organizations_info['house']}" ?>
                </p>
                <p>
                    <?= "Телефон {$organizations_info['phone']}" ?>
                </p>
                <h4>Часы работы:</h4>
                <?php
                    $time_to_work = $organizations_info['time_to_work'] ? explode(';', $organizations_info['time_to_work']) : '';
                ?>
                <p><?= $time_to_work[0] ?></p>
                <p><?= $time_to_work[1] ?></p>
                <p><?= $time_to_work[2] ?></p>
            </div>
        </div>
        <div class="col-xs-12 col-sm-8 col-md-4">
            <div class="footer__propr">
                <h3 class="footer_title">Платежные реквизиты</h3>
                <table>
                    <tbody>
                        <tr>
                            <td>Получатель платежа</td>
                            <td><?= $organizations_info['organizations_name'] ?></td>
                        </tr>
                        <tr>
                            <td>ИНН</td>
                            <td><?= $organizations_info['inn'] ?></td>
                        </tr>
                        <tr>
                            <td>КПП</td>
                            <td><?= $organizations_info['kpp'] ?></td>
                        </tr>
                        <tr>
                            <td>Расчетный счет</td>
                            <td><?= $organizations_info['checking_account'] ?></td>
                        </tr>
                        <tr>
                            <td>КС</td>
                            <td><?= $organizations_info['ks'] ?></td>
                        </tr>
                        <tr>
                            <td>БИК</td>
                            <td><?= $organizations_info['bic'] ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>
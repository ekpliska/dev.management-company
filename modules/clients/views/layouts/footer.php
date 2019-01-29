<?php

    use yii\helpers\Html;

/* 
 * Футер
 */
?>
<div class="footer">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-8 col-md-4">
                <div class="footer__dispatcher">
                    <h3 class="distapcher-phone">
                        <a href="tel:8800800808080" class="distapcher-phone">8 800 800-80-80</a>
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
                    <p>000000, Россия, г. Город,</p>
                    <p>Проспект, Улица, Строение, Корпус</p>
                    <p>Станция метро "Станция"</p>
                    <h4>Часы работы</h4>
                    <p>Понедельник-Пятница: 00:00 - 00:00</p>
                    <p>Суббота: 00:00 - 00:00</p>
                    <p>Обед: 00:00</p>
                </div>
            </div>
            <div class="col-xs-12 col-sm-8 col-md-4">
                <div class="footer__propr">
                    <h3 class="footer_title">Платежные реквизиты</h3>
                    <table>
                        <tbody>
                            <tr>
                                <td>Получатель платежа</td>
                                <td>#TODO</td>
                            </tr>
                            <tr>
                                <td>ИНН</td>
                                <td>#TODO</td>
                            </tr>
                            <tr>
                                <td>КПП</td>
                                <td>#TODO</td>
                            </tr>
                            <tr>
                                <td>Расчетный счет</td>
                                <td>#TODO</td>
                            </tr>
                            <tr>
                                <td>КС</td>
                                <td>#TODO</td>
                            </tr>
                            <tr>
                                <td>БИК</td>
                                <td>#TODO</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

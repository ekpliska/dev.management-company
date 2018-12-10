<?php

    use yii\helpers\Html;

/* 
 * Футер
 */
?>
<div class="footer">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-8 col-md-6">
                <div class="company">
                    <div class="company_image">
                        <?= Html::img('/images/footer/footer_logo.png', ['class' => 'footer-logo', 'alt' => 'image'])  ?>
                    </div>
                    <div class="company_info">
                        <h3>ELectronic Smart Assistant</h3>
                        <p>г. Город, ул. Улица, дом Дом</p>
                        <p>&copy; 2XXX &ndash; <?= date('Y') ?> </p>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-sm-8 col-md-6 text-right">
                #TODO
            </div>
        </div>
    </div>
</div>

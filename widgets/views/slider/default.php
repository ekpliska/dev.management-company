<?php

    use yii\helpers\Html;

/* 
 * Слайдер
 */
?>


<div id="general-page__slider" class="carousel slide" data-ride="carousel">
    <ol class="carousel-indicators">
        <li data-target="#general-page__slider" data-slide-to="0" class="active"></li>
        <?php for ($i = 1; $i <= $count_slider; $i++) : ?>
            <li data-target="#general-page__slider" data-slide-to="<?= $i ?>"></li>
        <?php endfor; ?>
    </ol>
    
    <div class="carousel-inner">
        <div class="item active">
            <div class="carousel-title">
                <h3>Добро пожаловать!</h3>
            </div>
            <div class="carousel-logo-company text-center">
                <?= Html::img('/images/slider/main_logo.png', ['class' => 'slider-logo', 'alt' => 'Elsa logo']) ?>
            </div>
        </div>
        
        <?php if (is_array($sliders) && count($sliders) > 0) : ?>
        <?php foreach ($sliders as $key => $slider) : ?>
            <div class="item">

                <?php if ($slider['button_1'] && $slider['button_2']) : ?>
                    <div class="text-center">
                        <?= Html::img('/images/slider/ELSA_product_icon.png', ['class' => 'slider-product-icon', 'alt' => 'Elsa logo']) ?>
                    </div>
                <?php endif; ?>
                
                <div class="carousel-title__small">
                    <?= Html::img('/images/main/h_logo.svg', ['class' => 'slider-title__img', 'alt' => 'Slider title']) ?>
                </div>

                <p class="slide-txt-block">
                    <?= !empty($slider['slider_text']) ? $slider['slider_text'] : '' ?>
                </p>

                <div class="app-btn-group text-center">
                    <?php if ($slider['button_1']) : ?>
                        <a href="<?= $slider['button_1'] ?>" class="store-btn-icon app-store-logo">App Store</a>
                    <?php endif; ?>
                    <?php if ($slider['button_2']) : ?>
                        <a href="<?= $slider['button_2'] ?>" class="store-btn-icon google-play-logo">Google Play</a>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
        <?php endif; ?>

    </div>
    
</div>

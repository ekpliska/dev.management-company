<?php

    use yii\helpers\Html;

/* 
 * Слайдер
 */
?>


<div id="myCarousel" class="carousel slide" data-ride="carousel">
    <ol class="carousel-indicators">
        <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
        <?php for ($i = 1; $i <= $count_slider; $i++) : ?>
            <li data-target="#myCarousel" data-slide-to="<?= $i ?>"></li>
        <?php endfor; ?>
    </ol>
    
    <div class="carousel-inner">
        <div class="item active">
            <?= Html::img('/images/slider/elsa-ground.png', ['class' => 'general-slider__image']) ?>
            <div class="carousel-caption">
                <h3 class="slider-title">Добро пожаловать!</h3>
                <?= Html::img('/images/slider/main_logo.png', ['class' => 'slider-logo', 'alt' => 'Elsa logo']) ?>
            </div>
        </div>
        
        <?php if (is_array($sliders) && count($sliders) > 0) : ?>
        <?php foreach ($sliders as $key => $slider) : ?>
        <div class="item">
            <?= Html::img('/images/slider/elsa-ground.png', ['class' => 'general-slider__image']) ?>
            <div class="carousel-caption">
                
                <?php if ($slider['button_1'] && $slider['button_2']) : ?>
                    <?= Html::img('/images/slider/ELSA_product_icon.png', ['class' => 'slider-product-icon', 'alt' => 'App icon']) ?>
                <?php endif; ?>
                
                <h3 class="slider-title">
                    <?= Html::img('/images/main/H-logo.svg', ['class' => 'slider-title__img', 'alt' => 'Slider title']) ?>
                </h3>
                
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
        </div>
        <?php endforeach; ?>
        <?php endif; ?>
        
    </div>
</div>
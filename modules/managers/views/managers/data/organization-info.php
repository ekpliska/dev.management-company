<?php

/* 
 * Данные об управляющей компании
 */
?>

<div class="__title">
    <h5>
        Информация об управляющей компании
    </h5>
</div>
<div class="__content">
    <?php if (isset($organization_info) && $organization_info) : ?>
        <div class="active_block__content">
            <div class="active_block__flex-item">
                <div class="__flex-item__cont">
                    <div class="__flex-item__cont-title">
                        Наименование управляющей компании
                    </div>
                    <div class="__flex-item__cont-value">
                        <?= $organization_info->organizations_name ?>
                    </div>
                </div>
                
            </div>
        </div>
    <?php else: ?>
         <p>
            Информация не доступна.
        </p>
        <?= Html::a('Настройки', ['managers/settings'], ['class' => 'active_block__link']) ?>
    <?php endif; ?>
</div>
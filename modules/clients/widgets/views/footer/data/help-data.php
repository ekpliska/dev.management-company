<?php

    use yii\bootstrap\Modal;

/* 
 * Ответы на часто задаваемые вопросы
 */
?>
<?php
Modal::begin([
    'id' => 'faq-modal',
    'header' => 'Ответы на часто задаваемые вопросы',
    'closeButton' => [
        'class' => 'close modal-close-btn changes_rent__close',
    ],
    'clientOptions' => [
        'backdrop' => 'static', 
        'keyboard' => false,
    ],
]);
?>
<?php if (!empty($faq_info)) : ?>
<div class="panel-group" id="accordion">
<?php foreach ($faq_info as $key => $faq) : ?>
    <div class="panel panel-default panel-faq">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion" href="<?= "#faq-{$key}" ?>">
                    <?= $faq->faq_question ?>
                    <span class="pull-right">
                        <i class="fa fa-chevron-down"></i>
                    </span>
                </a>
            </h4>
        </div>
        <div id="faq-<?= $key ?>" class="panel-collapse collapse">
            <div class="panel-body">
                <?= $faq->faq_answer ?>
            </div>
        </div>
    </div>
<?php endforeach; ?>
</div>
<?php else: ?>
    <p>Приносим свои извинения, блок частых вопросов еще не подготовлен.</p>
<?php endif; ?>
<?php Modal::end(); ?>


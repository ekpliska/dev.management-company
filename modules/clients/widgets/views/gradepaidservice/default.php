<?php

    use app\models\StatusRequest;

/* 
 * Виджет оценки для выполненной заявки на платную услугу
 */
?>
    
<?php if ($status == StatusRequest::STATUS_CLOSE) : ?>
    <div id=<?= "grade-{$id}" ?> data-rating="<?= $grade ?>" data-request="<?= $id ?>"></div>
<?php endif; ?>
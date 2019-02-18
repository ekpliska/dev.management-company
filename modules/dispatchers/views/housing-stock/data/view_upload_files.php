<?php

    use yii\helpers\Html;
    use app\helpers\FormatHelpers;

/* 
 * Прикрепленные документы
 */
?>
<?php if (isset($files) && $files) : ?>
    <table class="table table-characteristics table-striped ">
        <?php foreach ($files as $file) : ?>
        <tr>
            <td><?= FormatHelpers::formatUrlByDoc($file['name'], $file['filePath']) ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
<?php else: ?>
<div class="notice info">
    <p>Вложения отсутствуют</p>
</div>
<?php endif; ?>

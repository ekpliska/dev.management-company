<?php

    use yii\helpers\Html;
    use app\helpers\FormatHelpers;

/* 
 * Прикрепленные документы
 */
?>
<?php if (isset($files) && $files) : ?>
    <table width="100%" border="0" align="center" cellpadding="10" cellspacing="10">
        <?php foreach ($files as $file) : ?>
        <tr>
            <td><?= FormatHelpers::formatUrlByDoc($file['name'], $file['filePath']) ?></td>
            
            <td><?= Html::a('upload', ['/'], ['class' => 'btn btn-link btn-sm delete_file']) ?></td>
            
            <td><?= Html::button('<span class="glyphicon glyphicon-trash"></span>', [
                    'class' => 'btn btn-link btn-sm',
                    'data-files' => $file['id'],]) ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>

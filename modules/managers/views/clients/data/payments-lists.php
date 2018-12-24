<?php

/* 
 * Рендер вида Платежи
 */
?>


<?php if (isset($payments_lists)) : ?>
<tr>
    <td>Sample text</td>
    <td>Sample text</td>
    <td>Sample text</td>
    <td>Sample text</td>
</tr>
<?php else : ?>
<tr>
    <td colspan="4" class="status-not-found">
        Данный лицевой счет не содержит историю платежей или платежи не найдены.
    </td>
</tr>
<?php endif; ?>
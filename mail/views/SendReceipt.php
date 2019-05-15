<?php

/*
 * Отправка квитанции
 */
?>

<table border="0" cellpadding="10" cellspacing="10" width="100%" align="center" valign="middle" style="font-family: sans-serif; font-size: 14px;">
    <thead>
        <tr>
            <th colspan="2" style="border-bottom: 1px solid #dddddd;">
                <img src="<?= Yii::$app->urlManager->createAbsoluteUrl(['images/main/elsa_logo.png']) ?>" alt="ELSA LOGO" width="170">
                <p style="font-family: sans-serif; font-size: 12px; letter-spacing: 2; color: #331fb6;">
                    ELECTRONIC SMART ASSISTEN
                </p>
            </th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>
                Здравствуйте, <?= Yii::$app->userProfile->getFullNameClient() ?>
            </td>
        </tr>
        <tr>
            <td>
                Ваша квитанция <?= isset($receipt_number) ? $receipt_number : '' ?>, находится во вложении в данном письме.
            </td>
        </tr>
        <tr>
            <td>
                С уважением, компания <a href="<?= Yii::$app->urlManager->createAbsoluteUrl(['/']) ?>">ELSA</a>
            </td>
        </tr>
        <tr>
            <td colspan="2" style="font-size: 12px; color: #777777;" bgcolor="#dddddd">
                Как отписаться от рассылки:
                <ol>
                    <li>
                        Вы можете отписаться от рассылки в личном кабинете, раздел Профиль.
                    </li>
                    <li>
                        Или же перейдя по ссылке <a href="<?= Yii::$app->urlManager->createAbsoluteUrl(['/']) ?>">Отписаться от рассылки</a>
                    </li>
                </ol>
            </td>
        </tr>
    </tbody>
</table>
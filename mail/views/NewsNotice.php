<?php

    use yii\helpers\Html;
    use yii\helpers\Url;
    

/* 
 * Уведомление о новой новости
 */
?>

<table border="0" cellpadding="10" cellspacing="10" width="100%" align="center" valign="middle" style="font-family: sans-serif; font-size: 14px;">
    <thead>
        <tr>
            <th colspan="2" style="border-bottom: 1px solid #dddddd;">
                <img src="<?= Url::to('images/main/elsa_logo.png', true) ?>" alt="ELSA LOGO" width="170">
                <p style="font-family: sans-serif; font-size: 12px; letter-spacing: 2; color: #331fb6;">
                    ELECTRONIC SMART ASSISTEN
                </p>
            </th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td colspan="2">
                <?= Yii::$app->formatter->asDate(time(), 'd MMMM, YYYY') ?>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                Здравствуйте, вы подписанны на рассылку email-уведомлений на портале 
                <a href="<?= Url::home(true) ?>">ELSA</a>. 
                На нашем портале появилась публикация, которая, возможно вас заинтересует.
            </td>
        </tr>
        <tr>
            <td width="20%" rowspan="2">
                <img src="<?= Url::to($params['post_image'], true) ?>" alt="Image publish" width="240" style="display:block;">
            </td>
            <td>
                <?= Html::encode($params['post_title']) ?>
            </td>
        </tr>
        <tr>
            <td colspan="2" height="40">
                <?= Html::encode($params['post_text']) ?>
            </td>
        </tr>
        <tr>
            <td colspan="2" align="center">
                <a href="#" class="view-news" target="_blank">
                    Ознакомиться
                </a>
            </td>
        </tr>
        <tr>
            <td colspan="2" style="font-size: 12px; color: #777; border-top: 1px solid #dddddd;">
                Как отписаться от рассылки:
                <ol>
                    <li>
                        Вы можете отписаться от рассылки на портале в личном кабинете. Раздел Профиль.
                    </li>
                    <li>
                        Или перейдя по ссылке <a href="<?= Url::to(['/'], true) ?>">Отписаться от рассылки</a>
                    </li>
                </ol>
            </td>
        </tr>
    </tbody>
</table>
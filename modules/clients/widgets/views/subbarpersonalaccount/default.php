<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>

<?php if (Yii::$app->controller->id == 'personal-account') : ?>
<nav class="navbar navbar-expand-lg navbar-light bg-light navbar_personal-account my-navbar">
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="#">
                    Общая информация
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">
                    Квитанция ЖКУ
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">
                    Платежи
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">
                    Показания приборов учета
                </a>
            </li>
    </ul>
  </div>
</nav>
<?php endif; ?>
<?php
    
    use yii\helpers\Html;
    
/* 
 * Блок "Контактная информация"
 * Профиль
 */

?>
<div class="col-md-4" style="padding: 0;">
    <p>Фамилия</p>
    <?= Html::input('text', 'surname', $user_info['surname'], [
        'class' => 'form-control', 
        'disabled' => true]) 
    ?>
</div>
<div class="col-md-4" style="padding: 0;">
    <p>Имя</p>
    <?= Html::input('text', 'name', $user_info['name'], [
        'class' => 'form-control', 
        'disabled' => true]) 
    ?>
</div>
<div class="col-md-4" style="padding: 0;">
    <p>Отчество</p>
    <?= Html::input('text', 'second_name', $user_info['second_name'], [
        'class' => 'form-control', 
        'disabled' => true]) 
    ?>
</div>
                        
<p>Домашний телефон</p>
<div class="col-md-12" style="padding: 0;">
    <?= Html::input('text', 'phone', $user_info['phone'], [
        'class' => 'form-control', 
        'disabled' => true]) 
    ?>
</div>
                        
<p>Мобильный телефон</p>
<div class="col-md-12" style="padding: 0;">
    <?= Html::input('text', 'mobile', $user_info['mobile'], [
        'class' => 'form-control', 
        'disabled' => true]) 
    ?>
</div>
                        
<p>Электронная почта</p>
    <div class="col-md-12" style="padding: 0;">
    <?= Html::input('text', 'email', $user_info['email'], [
        'class' => 'form-control', 
        'disabled' => true])
    ?>
</div>
                        
<p>Номер лицевого счета</p>
    <div class="col-md-12" style="padding: 0;">
    <?php /*= Html::input('text', 'account-number',
            $info['account'], [
                'class' => 'form-control', 
                'disabled' => true, 
                'id' => 'account-number'
        ]) */
    ?>
</div>
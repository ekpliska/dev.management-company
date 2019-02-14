/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$(document).ready(function(){
    
    // ******************************************************** //
    // ***********    General Page of Dispaycher   ************ //
    // ******************************************************** //
    
    /*
     * Переключение списка заявок, на главной странице
     */
    $('.notice__user_block').on('click', function() {
        var userID = $(this).data('user');
        var type = $(this).data('type');
        console.log(userID + ' ' + type);
        var allBlock = $('.notice__user_block');
        allBlock.removeClass('notice__user__active');
        $(this).addClass('notice__user__active');
        $.ajax({
            url: 'show-user-requests',
            method: 'POST',
            data: {
                userID: userID,
                type: type,
            },
        }).done(function(response) {
            if (response.success === true) {
                $('#request_lists').html(response.data);
            }
        });
    });
    
    
    /*
     * Поиск собственника по введенному номеру телефона
     * Поиск срабатывает когда поле ввода теряем фокус
     */
    $('body').on('blur', '.mobile_phone', function() {
        // Получаем текущее значение
        var strValue = $(this).val();
        // В полученном значении удаляем все символы кроме цифр, знака -, (, )
        strValue = strValue.replace(/[^-0-9,(,)]/gim, '');
        $.post('show-houses?phone=' + strValue,
        function(data) {
            $('#house').html(data);
        });
    });
    
    /*
     * Формирование зависимых списков выбора имени услуги от ее категории
     */
    $(document).on('change', '#category_service', function() {
        $.post('show-name-service?categoryId=' + $(this).val(),
        function(data) {
            $('#service_name').html(data);
        });
    });
    
    
});
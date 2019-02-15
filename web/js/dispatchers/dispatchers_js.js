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
    
    /*
     * Вызов модального окна "Назначение диспетчера"
     * Вызов модального окна "Назначение специалиста"
     */
    $('#add-specialist-modal').on('show.bs.modal', function(e) {
        var requestId = $(e.relatedTarget).data('request');
        var employeeId = $(e.relatedTarget).data('employee');
        var typeRequest = $(e.relatedTarget).data('typeRequest');
        $('.error-message').text('');
        $('.add_specialist__btn').data('request', requestId);
        $('.add_specialist__btn').data('typeRequest', typeRequest);
        // Если сотрудник уже назначен, то обозначаем его активным в списке выбора сотрудников
        $('a[data-employee=' + employeeId + ']').addClass('active');
    });
    
    $('#add-specialist-modal').on('hide.bs.modal', function() {
        $('#specialistList').find('.active').removeClass('active');
    });
    
    /*
     * В списке сотрудников, выбранных специалистов обозначаем активными
     */
    $('#specialistList a').on('click', function() {
        var employeeId = $(this).data('employee');
        $('#specialistList').find('.active').removeClass('active');
        $(this).toggleClass('active');
        $('.add_specialist__btn').data('specialist', employeeId);
    });
    
    /*
     * Отправляем запрос на добавления специалиста к выбранной заявке
     */
    $('.add_specialist__btn').on('click', function(e) {
        e.preventDefault();
        // Получаем ФИО выбранного сотрудника
        var employeeName = $('#specialistList').find('.active').text();
        var specialistId = $(this).data('specialist');
        var requestId = $(this).data('request');
        var typeRequest = $(this).data('typeRequest');
        
        // Проверяем налицие дата параметров
        if (specialistId === undefined || requestId === undefined) {
            $('.error-message').text('Прежде чем назначить специалиста, выберите его из списка');
            return false;
        } else {
            $.ajax({
                url: 'choose-specialist',
                method: 'POST',
                data: {
                    specialistId: specialistId,
                    requestId: requestId,
                    typeRequest: typeRequest,
                },
                success: function(response) {
                    if (response.success === false) {
                        $('.error-message').text('Ошибка');
                        return false;
                    }
                    $('.btn-specialist').data('employee', specialistId);
                    $('#specialist-name').text('');
                    $('#specialist-name').html(employeeName);
                },
                error: function() {
                    $('.error-message').text('Ошибка');
                },
            });
        }
    });
    
    /*
     * Запрос на отклонение заявки
     */
    $('.reject-request').on('click', function() {
        $('#confirm-reject-request-message').modal('show');
    });    
    
    /*
     * Вызов модального окна "Назначение диспетчера"
     * Вызов модального окна "Назначение специалиста"
     */
    $('.request_reject_yes').on('click', function() {
        var button = $('.reject-request');
        var requestID = button.data('request');
        var requestStatus = button.data('status');
        var requestType = button.data('typeRequest');
        console.log(button + ' ' + requestID + ' ' + requestStatus + ' ' + requestType);
        $.ajax({
            url: 'confirm-reject-request',
            method: 'POST',
            data: {
                requestID: requestID,
                requestStatus: requestStatus,
                requestType: requestType,
            },
            success: function(response) {
                if (response.success === true) {
                    
                }
                console.log(response);
            },
            error: function() {
                $('.error-message').text('Ошибка');
            },
        });
    });    
    
       
    /*
     * Сброс форм 
     *      Новая завяка
     *      Новая заявка на платную услугу
     */
    $('.create-request, .create-paid-request').on('click', function(){
        $('#create-new-request, #create-new-paid-request')[0].reset();
    });
    
});
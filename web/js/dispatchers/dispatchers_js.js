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
        console.log(employeeName + ' ' + specialistId + ' ' + requestId + ' ' + typeRequest);
        
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
                    $('.btn-employee').data('employee', specialistId);
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
     * Смена статуса "Отклонена" для заявок и платных услуг
     */
    $('.request_reject_yes').on('click', function() {
        var button = $('.reject-request');
        var requestID = button.data('request');
        var requestStatus = button.data('status');
        var requestType = button.data('typeRequest');
        
        $.ajax({
            url: 'confirm-reject-request',
            method: 'POST',
            data: {
                requestID: requestID,
                requestStatus: requestStatus,
                requestType: requestType,
            },
            success: function(response) {
                if (response.success === false) {
                    $('#confirm-request-error').modal('show');
                    return false;
                }
                
                $('.reject-request').hide();
                $('.blue-btn').hide();
                // Перерисовываем класс у статуса заявки
                $('.badge-page').removeClass(function (index, classNames) {
                    var classList = classNames.split(' ');
                    $('.badge-page').removeClass(classList[0]);
                    $('.badge-page').addClass('req-badge-reject-page');
                    $('.badge-page span:first-child').text(response.status_name);
                });                
                
                console.log(response);
            },
            error: function() {
                $('#confirm-request-error').modal('show');
            },
        });
    });    
    
       
    /*
     * Сброс форм 
     *      Новая завяка
     *      Новая заявка на платную услугу
     */
//    $('.create-request, .create-paid-request').on('click', function(){
//        $('#create-new-request, #create-new-paid-request')[0].reset();
//    });
    

    // ******************************************************** //
    // ***********    General Page of Dispaycher   ************ //
    // ******************************************************** //
    
    /*
     * Переключение домов
     */
    $(document).on('click', '#house_link', function() {
        var house = $(this).attr('href');
        var key = $(this).data('key');
        house = house.replace(/[^0-9]/gim, '');
//        console.log('--', house, '--', key);
        
        $.ajax({
            url: 'view-characteristic-house',
            method: 'POST',
            data: {
                key: key,
                house: house,
            },
            success: function (response) {
                $('#characteristic_list').html(response.data);
                $('#flats_list').html(response.flats);
                $('#files_list').html(response.files);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(textStatus);
            }
        });
    });
   
/* Кастомизация элеметнов управления формой, категории услуг */
    $(".custom-select-dark").each(function() {
        var classes = $(this).attr("class"),
            id = $(this).attr("id"),
            name = $(this).attr("name");
        var template =  '<div class="' + classes + '">';
            template += '<span class="custom-select-trigger-dark">' + $(this).attr("placeholder") + '</span>';
            template += '<div class="custom-options-dark">';
        // Текущий выбранный лицевой счет
        var currentValue = $('#select-dark option:selected').val();
        // ID Собственника
        var currentClient = $('#select-dark').data('client');
        // Текущий экшен
        var currentAction = $('#select-dark').data('url');
        
        $(this).find("option").each(function() {
            var classSelection = ($(this).attr("value") == currentValue) ? 'selection-dark ' : '';            
            template += '<a href="' + currentAction + '?client_id=' + currentClient + '&account_number=' + $(this).text() + '" class="custom-option-dark ' + classSelection + $(this).attr("class") 
                        + '" data-value="' + $(this).attr("value") + '">' 
                        + $(this).html() + '</a>';
            
            $(this).val('selection-dark');
            
        });
        template += '</div></div>';

        $(this).wrap('<div class="custom-select-wrapper-dark"></div>');
        $(this).hide();
        $(this).after(template);
    });

    $(".custom-option-dark:first-of-type").hover(function() {
        $(this).parents(".custom-options-dark").addClass("option-hover-dark");
    }, function() {
        $(this).parents(".custom-options-dark").removeClass("option-hover-dark");
    });

    $(".custom-select-trigger-dark").on("click", function() {
        $('html').one('click',function() {
            $(".custom-select-dark").removeClass("opened");
        });
        $(this).parents(".custom-select-dark").toggleClass("opened");
        event.stopPropagation();
    });
    
    $(".custom-option-dark").on("click", function() {
        var valueSelect = $(this).data("value");
        var textSelect = $(this).text();
        $(this).parents(".custom-select-wrapper-dark").find("select").val(valueSelect);
        $(this).parents(".custom-options-dark").find(".custom-option-services").removeClass("selection-dark");
        $(this).addClass("selection-dark");
        $(this).parents(".custom-select-dark").removeClass("opened");
        $(this).parents(".custom-select-dark").find(".custom-select-trigger-dark").text(textSelect);
    });    
    
});
/* 
 * For Dispatcher of Modules
 */

$(document).ready(function(){
    
    // ******************************************************** //
    // ***********    General Page of Dispatcher   ************ //
    // ******************************************************** //
    
    /*
     * Переключение списка заявок, на главной странице
     */
    $('.notice__user_block').on('click', function() {
        var userID = $(this).data('user');
        var type = $(this).data('type');
        var allBlock = $('.notice__user_block');
        allBlock.removeClass('notice__user__active');
        $(this).addClass('notice__user__active');
        $.ajax({
            url: '/dispatchers/dispatchers/show-user-requests',
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
        $.post('/dispatchers/dispatchers/show-houses?phone=' + strValue,
        function(data) {
            $('#house').html(data);
        });
    });
    
    /*
     * Формирование зависимых списков выбора имени услуги от ее категории
     */
    $(document).on('change', '#category_service', function() {
        $.post('/dispatchers/requests/show-name-service?categoryId=' + $(this).val(),
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
        
        // Проверяем налицие дата параметров
        if (specialistId === undefined || requestId === undefined) {
            $('.error-message').text('Прежде чем назначить специалиста, выберите его из списка');
            return false;
        } else {
            $.ajax({
                url: '/dispatchers/requests/choose-specialist',
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
            url: '/dispatchers/requests/confirm-reject-request',
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
                $('.dropdown-menu').find('.disabled').removeClass('disabled');
                $('#value-btn').html(response.status_name + '<span class="caret"></span>');
                $('.btn-group_status-request').attr('id', 'status-value-' + requestStatus);
                $('#value-btn').removeClass('add-border-' + $('#value-btn').data('status'));
                $('#value-btn').data('status', requestStatus);
                $('#value-btn').addClass('add-border-' + requestStatus);
            },
            error: function() {
                $('#confirm-request-error').modal('show');
            },
        });
    });

    /*
     * Переключение статуса заявки
     */
    $('.switch-request').on('click', function (e) {
        e.preventDefault();
        var linkValue = $(this).text();
        var statusId = $(this).data('status');
        var requestId = $(this).data('request');
        var liChoosing = $('li#status' + statusId);
        var typeRequest = $(this).data('typeRequest');
        $.ajax({
            url: '/dispatchers/requests/switch-status-request',
            method: 'POST',
            data: {
                statusId: statusId,
                requestId: requestId,
                typeRequest: typeRequest,
            },
            success: function (response) {
                if (response.status) {
                    $('.dropdown-menu').find('.disabled').removeClass('disabled');
                    $('#value-btn').html(linkValue + '<span class="caret"></span>');
                    $('.btn-group_status-request').attr('id', 'status-value-' + statusId);
                    $('#value-btn').removeClass('add-border-' + $('#value-btn').data('status'));
                    $('#value-btn').data('status', statusId);
                    $('#value-btn').addClass('add-border-' + statusId);
                    liChoosing.addClass('disabled');
                }
            },
            error: function () {},
        });
    });

    /*
     * Запрос на отключение чата у заявки
     */
    $(document).on('click', '#close-chat', function () {
        var requestID = $(this).data('request');
        $.post('/dispatchers/requests/confirm-close-chat?request_id=' + requestID, function (data) {});
    });
    
    /*
     * Поиск по специалистам
     */
    function searchEmployee() {
        var input, filter, ul, li, a, i, txtValue;
        input = document.getElementById('search-employee');
        filter = input.value.toUpperCase();
        ul = document.getElementById('employees-list');
        li = ul.getElementsByTagName('li');
        for (i = 0; i < li.length; i++) {
            a = li[i].getElementsByTagName('a')[0];
            txtValue = a.textContent || a.innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                li[i].style.display = '';
            } else {
                li[i].style.display = 'none';
            }
        }
    }
    
    $('#search-employee').on('input', function() {
        searchEmployee();
    });    

    // ******************************************************** //
    // ***********    General Page of Dispatcher   ************ //
    // ******************************************************** //
    
    /*
     * Переключение домов
     */
    $(document).on('click', '#house_link', function() {
        var house = $(this).attr('href');
        var key = $(this).data('key');
        house = house.replace(/[^0-9]/gim, '');
        
        $.ajax({
            url: '/dispatchers/housing-stock/view-characteristic-house',
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
            error: function (jqXHR, textStatus, errorThrown) {}
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
            template += '<a href="' + $(this).text() + '" class="custom-option-dark ' + classSelection + $(this).attr("class") 
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
    
    
    // ******************************************************** //
    // ************     Start Block of News      ************** //
    // ******************************************************** //
    /*
     * Переключатель, статус публикации новости
     *      Для всех
     *      Для жилого комплекса
     *      Для отдельного дома
     */
    $('#for_whom_news').on('change', function(e) {
        var forWhom = $("#news-form input[type='radio']:checked").val();
        
        if (forWhom === '0') {
            $('#adress_list').prop('disabled', true);
        } else {
            $('#adress_list').prop('disabled', false);
        }
        
        $.post('/dispatchers/news/for-whom-news?status=' + forWhom,
            function(data) {
                $('#adress_list').html(data);
            }
        );
    });
    
    /*
     * Если выбран параметр "Реклама" то список партнеров делаем доступным
     */
    $('#check_advet').on('change', function(){
       if ($('#check_advet').is(':checked')) {
           $('#parnters_list').prop('disabled', false);
       } else {
           $('#parnters_list').val('0');
           $('#parnters_list').prop('disabled', true);
       }
    });
    
    /*
     * Запрос на удаление прикрепленного документа
     */
    $('.delete_span').on('click', function() {
        var fileId = $(this).data('files');
        $(this).html('');
        $(this).append(
                '<a href="javascript:void(0)" id="delete-file-yes-' + fileId + '" data-files="' + fileId + '">Удалить</a>' + '&nbsp;&nbsp;&nbsp;' + 
                '<a href="javascript:void(0)" id="delete-file-no-' + fileId + '" data-files="' + fileId + '">Восстановить</a>'
                );
    });
    
    $(document).on('click' , 'a[id^=delete-file-yes]', function(e){
        var fileId = $(this).data('files');
        e.preventDefault();
        $.post('/dispatchers/news/delete-file?file=' + fileId, function(data) {
            $('a[id^=delete-file-yes-' + fileId + ']').closest('tr').remove();
            e.preventDefault(); 
        });
    });
    
    $(document).on('click' , 'a[id^=delete-file-no]', function(e){
        var fileId = $(this).data('files');
        $('#delete-file-no-' + fileId).parent().html('&times;');
    });    
    
    // *********************************************************** //
    // ************     Start Block of Profile      ************** //
    // ********************************************************** //
    /*
     * Список квитанций, Профиль Собственника
     */
    $(document).on('click', '.list-group-item', function() {
        var liItem = $(this).data('receipt');
        var accountNumber = $(this).data('account');
        var url = location.origin + '/receipts/' + accountNumber + '/' + accountNumber + '-' + liItem + '.pdf';
        var conteiner = $('.receipts_body');
        $('ul.receipte-of-lists li').removeClass('active');
        $(this).addClass('active');
        
        // Проверяем сущестование pdf, если существует - загружаем фрейм
        $.get(url)
                .done(function (){
                    conteiner.html('<iframe src="' + url + '" style="width: 100%; height: 600px;" frameborder="0">Ваш браузер не поддерживает фреймы</iframe>');
                }).fail(function(){
                    conteiner.html('<div class="notice error"><p>Квитанция на сервере не найдена.</p></div>');
                });
        });

        /*
         * Функция парсинга даты
         * Смена номер месяца и дня местами
         */
        function dateParse(date) {
            var dateArray = date.split('-');
            var dateString = dateArray[1] + '-' + dateArray[0] + '-' + dateArray[2];
            var newDate = new Date(dateString);
            return newDate;
        }
    
        /*
         * 
         * Общий метод формирования AJAX запросов для профиль Собственника, ЛК Администратор
         * @param {type} accountNumber Лицевой счет
         * @param {type} startDate Дата начала даипазона
         * @param {type} endDate Дата конца диапазона
         * @param {type} idError ID блока, в котором будет выведены ошибки запроса
         * @param {type} idContent ID блока, в котором будет выведен результат запроса
         * @param {type} type Тип запроса (Квитанции, Платежи, Приборы учета)
         * @returns {undefined}
         */
        function getDataClients (accountNumber, startDate, endDate, idContent, type) {
            var parseStartDate = dateParse(startDate);
            var parseEndDate = dateParse(endDate);

            if (parseStartDate - parseEndDate > 0) {
                $('.message-block').addClass('invalid-message-show').html('Вы указали некорректный диапазон');
            } else if (parseStartDate - parseEndDate <= 0) {
                $('.message-block').removeClass('invalid-message-show').html('');
                $.post('/dispatchers/clients/search-data-on-period?account_number=' + accountNumber + '&date_start=' + startDate + '&date_end=' + endDate + '&type=' + type,
                    function(data) {
                        if (data.success === false) {
                            $('.message-block').addClass('invalid-message-show').html('Ошибка запроса');
                        } else if (data.success === true) {
                            $('.message-block').removeClass('invalid-message-show').html('');
                            $(idContent).html(data.data_render);
                        }
                    }
                );
            }

            return;

        }
    
    /*
     * Запрос на получение списка квитанций в заданный диапазон
     */
    $('#get-receipts').on('click', function(){
        var accountNumber = +$('#select-dark :selected').text();
        var startDate = $('input[name="date_start-period"]').val();
        var endDate = $('input[name="date_end-period"]').val();
        
        getDataClients(accountNumber, startDate, endDate, '#receipts-lists', 'receipts');
        
    });
    
    /*
     * Список платежей, профиль Собственника
     */
    $('.btn-show-payment').on('click', function(){
        var accountNumber = +$('#select-dark :selected').text();
        var startDate = $('input[name="date_start-period-pay"]').val();
        var endDate = $('input[name="date_end-period-pay"]').val();

        getDataClients(accountNumber, startDate, endDate, '#payments-lists', 'payments');
        
    });
    
    var month = {
        'январь': '01',
        'февраль': '02',
        'март': '03',
        'апрель': '04',
        'май': '05',
        'июнь': '06',
        'июль': '07',
        'август': '08',
        'сентябрь': '09',
        'октябрь': '10',
        'ноябрь': '11',
        'декабрь': '12',
    };
    
    /*
     * Запрос на формирование предыдущих показаний приборов учета
     */
    $('#show-prev-indication').on('click', function() {
        var dateValue = $('input[name=date_start-period-pay').val();
        var nameMonth = dateValue.split('-')[0];
        var year = dateValue.split('-')[1];
        var monthNumber = month[nameMonth.toLowerCase()];
        var accountNumber = $(this).data('account');
        
        if (!$.isNumeric(monthNumber) || !$.isNumeric(year)) {
            $('.message-block').addClass('invalid-message-show').html('Ошибка запроса');
            return false;
        }
        
        $.post('/dispatchers/clients/find-indications?month=' + monthNumber + '&year=' + year + '&account=' + accountNumber, function(response) {
            $('#indication-table').html(response.result);
            $('.message-block').removeClass('invalid-message-show').html('');
        });
    });
    
    
});
/* 
 * For Managers of Modules
 */

$(document).ready(function() {
    
    // ******************************************************** //
    // ************    Start Block of Profile    ************** //
    // ******************************************************** //
    /*
     * Формирование зависимых списков выбора Подразделения и Должности Администратора
     */
    $('.department_list').on('change', function(e) {
        $.post('/managers/app-managers/show-post?departmentId=' + $(this).val(),
        function(data) {
            $('.posts_list').html(data);
        });
    });
    
    /*
     * Блокировать/Разблокировать Учетную запись Собственника на портале
     */
    $('body').on('click', '.block_user', function(e) {
        e.preventDefault();
        var userId = $(this).data('user');
        var statusUser = $(this).data('status');        
        $.ajax({
            url: '/managers/app-managers/block-user-in-view',
            method: 'POST',
            data: {
                userId: userId,
                statusUser: statusUser,
            },
            success: function(response) {
                if (response.status == 2) {
                    $('.block_user').text('Разблокировать');
                    $('.block_user').removeClass('btn-block-user');
                    $('.block_user').addClass('btn-unblock-user');
                    $('.block_user').data('status', 1);
                } else {
                    if (response.status == 1) {
                        $('.block_user').text('Заблокировать');
                        $('.block_user').addClass('btn-block-user');
                        $('.block_user').removeClass('btn-unblock-user');
                        $('.block_user').data('status', 2);
                    }
                }
            },
            error: function() {
                console.log('Error #2000-01');
            },
        });
        return false;
    });
    
    /*
     * Вывоз модального окна, перед удаление собсвенника
     */
    $('#delete_clients_manager').on('show.bs.modal', function(e){
        var buttonTarget = $(e.relatedTarget);
        var dataContent = buttonTarget.data('user');
        $(this).find('#delete_client__del').data('user', dataContent);
    });
    
    $('#delete_client__del').on('click', function(){
        var dataContent = $(this).data('user');
        $.ajax({
            url: '/managers/clients/delete-client',
            method: 'POST',
            data: {clientId: dataContent}
        }).done(function(response){
            console.log(response);
        });
    });
    
    /*
     * Список квитанций, Профиль Собственника
     */
    $(document).on('click', '.list-group-item', function() {
        
        var house = $(this).data('house'),
            period = $(this).data('period'),
            account = $(this).data('account'),
            conteiner = $('.receipts_body');
            
        $('ul.receipte-of-lists li').removeClass('active');
        $(this).addClass('active');
        
        $.ajax({
            url: '/managers/clients/get-receipt-pdf',
            method: 'POST',
            data: {
                house: house,
                period: period,
                account: account
            }
        }).done(function(data){
            if (data.success === true) {
                conteiner.html('<iframe src="' + data.url + '" style="width: 100%; height: 670px;" frameborder="0">Ваш браузер не поддерживает фреймы</iframe>');
            } else if (data.success === false) {
                conteiner.html('<div class="notice error"><p>Квитанция ' + period + ' на сервере не найдена.</p></div>');
            }
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
     * @param {type} house Дом
     * @param {type} startDate Дата начала даипазона
     * @param {type} endDate Дата конца диапазона
     * @param {type} idError ID блока, в котором будет выведены ошибки запроса
     * @param {type} idContent ID блока, в котором будет выведен результат запроса
     * @param {type} type Тип запроса (Квитанции, Платежи, Приборы учета)
     * @returns {undefined}
     */
    function getDataClients (accountNumber, house, startDate, endDate, idContent, type) {
        var parseStartDate = dateParse(startDate);
        var parseEndDate = dateParse(endDate);
        
        if (parseStartDate - parseEndDate > 0) {
            $('.message-block').addClass('invalid-message-show').html('Вы указали некорректный диапазон');
        } else if (parseStartDate - parseEndDate <= 0) {
            $('.message-block').removeClass('invalid-message-show').html('');
            $.post('/managers/clients/search-data-on-period?account_number=' + accountNumber + '&house=' + house + '&date_start=' + startDate + '&date_end=' + endDate + '&type=' + type,
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
        var house = +$(this).data('house');
        var startDate = $('input[name="date_start-period"]').val();
        var endDate = $('input[name="date_end-period"]').val();
        
        getDataClients(accountNumber, house, startDate, endDate, '#receipts-lists', 'receipts');
        
    });
    
    /*
     * Список платежей, профиль Собственника
     */
    $('.btn-show-payment').on('click', function(){
        var accountNumber = +$('#select-dark :selected').text();
        var startDate = $('input[name="date_start-period-pay"]').val();
        var endDate = $('input[name="date_end-period-pay"]').val();

        getDataClients(accountNumber, null, startDate, endDate, '#payments-lists', 'payments');
        
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
        
        $.post('/managers/clients/find-indications?month=' + monthNumber + '&year=' + year + '&account=' + accountNumber, function(response) {
            $('#indication-table').html(response.result);
        });
    });
    
    /*
     * Валидация ввода показаний приборов учета
     * @type $|_$
     */
    $('input[class*="val_cr_"]').on('input', function () {
        var currentIndicaton = +$(this).val();
        var parentContainer = $(this).parent();
        var tdBlock = $(this).closest('td');
        var previousIndication = +tdBlock.find('input:first').val();
        if (currentIndicaton < previousIndication || currentIndicaton == 0 || !$.isNumeric(currentIndicaton)) {
            $(parentContainer).removeClass('has-success').addClass('has-error');
            $(parentContainer).find('.help-block').text('Некорректные показания');
            tdBlock.css('border', '1px solid #f28a71');
        } else {
            $(parentContainer).removeClass('has-error').addClass('has-success');
            $(parentContainer).find('.help-block').text('');
            tdBlock.css('border', 'none');
        }
        console.log(currentIndicaton);
    });
    
    /*
     * Раздел 'Приборы учета'
     * Поля ввода для показания приборов учета блокируем
     */ 
    $('.reading-input').on('input', function() {
        var uniqueCounter = $(this).data('uniqueCounter');
        $("button[id=send-indication-" + uniqueCounter + "]").prop('disabled', false);
    });
    
    $('.send-indication-counter').on('click', function(e) {
        var uniqueCounter = +$(this).data('uniqueCounter'),
            prevIndication = +$(this).data('prevIndication'),
            curIndication = +$("input[name=" + uniqueCounter + "_current_indication]").val(),
            labelMess = $("label[class=error-ind-" + uniqueCounter + "]");
            resultMess = $("span[id=result-" + uniqueCounter + "]");
        
        var isCheck = (!curIndication || curIndication <= prevIndication) ? false : true;
        
        if  (isCheck === false) {
            labelMess.text('Показание указано не верно');
            $(this).prop('disabled', true);
            e.preventDefault();
        } else if (isCheck === true) {
            labelMess.text('');
            $.post('/managers/clients/send-indication?counter=' + uniqueCounter + '&indication=' + curIndication, function(responce) {
                if (responce.success === false) {
                    labelMess.text('Ошибка отправки показаний');
                    return false;
                } else if (responce.success === true) {
                    labelMess.text('Показания отправлены');
                    resultMess.text((curIndication - prevIndication).toFixed(2));
                }
            });
        }
    }); 
    
    /*
     * Формирование автоматической заявки на платную услугу
     * "Поверка приборов учета"
     */
    $('button[id*="create-request-"').on('click', function() {
        var button = $(this).attr('id');
        var accountID = $(this).data('account');
        var typeCounter = $(this).data('counterType');
        var idCounter = $(this).data('counterId');
        console.log(button, '--', accountID, '--', typeCounter, '--', idCounter);
        
        $.ajax({
            url: '/managers/clients/create-paid-request',
            method: 'POST',
            data: {
                accountID: accountID,
                typeCounter: typeCounter,
                idCounter: idCounter,
            },
            success: function (data) {
                if (data.success == true) {
                    $('#' + button).replaceWith('<span class="message-request">Сформирована заявка ID' + data.request_number + '</span>');
                }
            },
            error: function () {}
        });
    });
    
    $('#delete-note-counters').on('click', function(){
        var noteId = $(this).data('note');
        $.post('/managers/clients/delete-note-counters?id=' + noteId, function(response) {
            console.log(response);
        });
    });
    
    /*
     * Приборы учета, профиль Собственника
     */
    
    // ******************************************************** //
    // ************    Start Block of Employers    ************** //
    // ******************************************************** //
    
    /*
     * Поиск по Диспетчерам
     */
    $('#_search-dispatcher').on('input', function() {
    
        var searchValue = $(this).val();
        
        $.ajax({
            url: 'search-dispatcher',
            method: 'POST',
            data: {
                searchValue: searchValue,
            },
            success: function(response) {
                $('.grid-view').html(response.data);
            },
            error: function() {
                console.log('Error #2000-10');
            }
        });
    });

    /*
     * Загрузка данных о Сотруднике модальное окно "Удалить сотрудника"
     */
    $('#delete_employee_manager').on('show.bs.modal', function(e) {
        // Обращаемся к кнопке, которая открыла модальное окно
        var button = $(e.relatedTarget);
        // Получаем ее дата атрибут
        var dataDis = button.data('employee');
        var dataFullName = button.data('fullName');
        var roleUser = button.data('role');
        $('#delete_employee_manager').find('#disp-fullname').text(dataFullName);
        $(this).find('#confirm_delete-empl').data('employer', dataDis);
        $(this).find('#confirm_delete-empl').data('role', roleUser);
    });    

    /*
     * Запрос на удаление профиля сотрудника
     */
    $('.delete_empl__del').on('click', function(){
        var employerId = $(this).data('employer');
        var role = $(this).data('role');
        $.ajax({
            url: '/managers/app-managers/query-delete-employee',
            method: 'POST',
            dataType: 'json',
            data: {
                employerId: employerId,
                role: role,
            },
            success: function(response) {
                if (response.isClose === true) {                
                    $('#delete_employee_error').modal('show');
                } else if (response.isClose === false) {
                    console.log('все заявки закрыты');
                }
            },
            error: function(){
                console.log('Error #2000');
            },
        });
    });

    /*
     * Поиск по Диспетчерам
     */
    $('#_search-specialist').on('input', function() {
    
        var searchValue = $(this).val();
        
        $.ajax({
            url: 'search-specialist',
            method: 'POST',
            data: {
                searchValue: searchValue,
            },
            success: function(response) {
                $('.grid-view').html(response.data);
            },
            error: function() {
                console.log('Error #2000-10');
            }
        });
    });
    
    /*
     * Сброс формы, смена пароля
     */
    $('.changes-password-form_close').on('click', function () {
        $('#changes-password-form')[0].reset();
    });    
    
    // ******************************************************** //
    // ************    Start Block of Service    ************** //
    // ******************************************************** //
    /*
     * Перед выводом модального окна на подтверждение удаления услуги 
     * Формируем характеристики удаляемой услуги
     */
    $('#delete_service').on('show.bs.modal', function(e){
        // Обращаемся к кнопке, которая открыла модальное окно
        var button = $(e.relatedTarget);
        // Получаем ее дата атрибут
        var dataSrv = button.data('service');
        $(this).find('#srv_name').text(button.data('serviceName'));
        $('#delete_service').find('.delete_srv__del').data('service', dataSrv);
    });
    
    /*
     * Запрос на удаление услуги
     */
    $('.delete_srv__del').on('click', function() {
        var serviceId = $(this).data('service');
        $.ajax({
            url: 'confirm-delete-service',
            method: 'POST',
            data: {
                serviceId: serviceId,
            },
            success: function(response) {
                // console.log(response.here);
            },
            error: function() {
                console.log('error');
            }
        });
    });
    
    /*
     * Сквозной поиск по таблице услуги
     */
    $('#_search-service').on('input', function() {
        var searchValue = $(this).val();
        $.ajax({
            url: 'search-service',
            method: 'POST',
            data: {
                searchValue: searchValue,
            },
            success: function(response) {
                $('.grid-view').html(response.data);
            },
            error: function() {
                console.log('Error #2000-10');
            }
        });
    });


    // ******************************************************** //
    // ************    Start Block of Requests    ************** //
    // ******************************************************** //
    /*
     * Формирование зависимых списков выбора имени услуги от ее категории
     */
    $(document).on('change', '#category_service', function(e) {
        $.post('/managers/app-managers/show-name-service?categoryId=' + $(this).val(),
        function(data) {
            $('#service_name').html(data);
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
        $.post('/managers/app-managers/show-houses?phone=' + strValue,
        function(data) {
            $('#house').html(data);
        });
    });
    
    /*
     * Переключение статуса заявки
     */
    $('.switch-request, .reject-request').on('click', function(e){
        e.preventDefault();
        var linkValue = $(this).text();
        var statusId = $(this).data('status');
        var requestId = $(this).data('request');
        var liChoosing = $('li#status' + statusId);
        var typeRequest = $(this).data('typeRequest');
        $.ajax({
            url: '/managers/requests/switch-status-request',
            method: 'POST',
            data: {
                statusId: statusId,
                requestId: requestId,
                typeRequest: typeRequest,
            },
            success: function(response) {
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
            error: function() {
//                console.log('error');
            },
        });
    });

    /*
     * Вызов модального окна "Назначение диспетчера"
     * Вызов модального окна "Назначение специалиста"
     */
    $('#add-dispatcher-modal, #add-specialist-modal').on('show.bs.modal', function(e) {
        var requestId = $('.switch-request-status').data('request');
        var employeeId = $(e.relatedTarget).data('employee');
        var typeRequest = $(e.relatedTarget).data('typeRequest');
        $('.error-message').text('');
        $('.add_dispatcher__btn').data('request', requestId);
        $('.add_dispatcher__btn').data('typeRequest', typeRequest);
        $('.add_specialist__btn').data('request', requestId);
        $('.add_specialist__btn').data('typeRequest', typeRequest);
        // Если сотрудник уже назначен, то обозначаем его активным в списке выбора сотрудников
        $('a[data-employee=' + employeeId + ']').addClass('active');
    });
    
    $('#add-dispatcher-modal, #add-specialist-modal').on('hide.bs.modal', function() {
        $('#dispatcherList, #specialistList').find('.active').removeClass('active');
    });

    /*
     * В списке сотрудников, выбранных диспетчеров/специалистов обозначаем активными
     */
    $('#dispatcherList a, #specialistList a').on('click', function() {
        var employeeId = $(this).data('employee');
        $('#dispatcherList, #specialistList').find('.active').removeClass('active');
        $(this).toggleClass('active');
        $('.add_dispatcher__btn').data('dispatcher', employeeId);
        $('.add_specialist__btn').data('specialist', employeeId);
    });

    /*
     * Отправляем запрос на добавления диспетчера к выбранной заявке
     */
    $('.add_dispatcher__btn').on('click', function(e) {
        e.preventDefault();
        // Получаем ФИО выбранного сотрудника
        var employeeName = $('#dispatcherList').find('.active').text();
        var dispatcherId = $(this).data('dispatcher');
        var requestId = $(this).data('request');
        var typeRequest = $(this).data('typeRequest');

        // Проверяем налицие дата параметров
        if (dispatcherId === undefined || requestId === undefined) {
            $('.error-message').text('Прежде чем назначить диспетчера, выберите его из списка');
            return false;
        } else {
            $.ajax({
                url: '/managers/requests/choose-dispatcher',
                method: 'POST',
                data: {
                    dispatcherId: dispatcherId,
                    requestId: requestId,
                    typeRequest: typeRequest,
                },
                success: function(response) {
                    if (response.success === false) {
                        $('.error-message').text('Ошибка');
                        return false;
                    }
                    
                    $('.btn-dispatcher').data('employee', dispatcherId);
                    $('#dispatcher-name').text('');
                    $('#dispatcher-name').html(
                            '<a href="/managers/employee-form/employee-profile/dispatcher/' + dispatcherId + '">' + 
                            employeeName + '</a>');
                },
                error: function() {
                    $('.error-message').text('Ошибка');
                },
            });
        }
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
                url: '/managers/requests/choose-specialist',
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
                    $('#specialist-name').html(
                            '<a href="/managers/employee-form/employee-profile/specialist/' + specialistId + '">' + 
                            employeeName + '</a>');
                },
                error: function() {
                    $('.error-message').text('Ошибка');
                },
            });
        }
    });

    /*
     * Сброс форм 
     *      Новая завяка
     *      Новая заявка на платную услугу
     */
    $('.create-request, .create-paid-request').on('click', function(){
        $('#create-new-request, #create-new-paid-request')[0].reset();
    });
    
    /*
     * Поиск по специалистам
     */
    function searchEmployee(type) {
        var input, filter, ul, li, a, i, txtValue;
        input = document.getElementById('search-employee-' + type);
        filter = input.value.toUpperCase();
        ul = document.getElementById('employees-list-' + type);
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
    
    $('#search-employee-dispatcher, #search-employee-specialist').on('input', function() {
        let type = $(this).data('type');
        console.log(type);
        searchEmployee(type);
    });
    
    /*
     * Загрузка модального окна для редактирования Заявки
     */
    $('a.edit-request-btn').on('click', function(e){
        var link = $(this).attr('href');
        $('#edit-requests').modal('show');
        $('#edit-requests .modal-dialog .modal-content .modal-body').load(link);
        e.preventDefault();
        return false;        
    });
    
    /*
     * Загрузка модального окна просмотр отзыва
     */
    $('a#show-grade-btn').on('click', function(e){
        var link = $(this).attr('href');
        $('#show-grade-modal').modal('show');
        $('#show-grade-modal .modal-dialog .modal-content .modal-body').load(link);
        e.preventDefault();
        return false;        
    });
    
    /*
     * Обработка запроса на удаление Заявки, Платной услуги
     */
    $('#delete-request-message').on('show.bs.modal', function(e){
        var requestId = $(e.relatedTarget).data('request');
        var requestType = $(e.relatedTarget).data('requestType');
        $('#delete-request-message').find('.delete_request').data('request', requestId);
        $('#delete-request-message').find('.delete_request').data('request-type', requestType);
    });
    
    $('.delete_request').on('click', function() {
        var requestId = $(this).data('request');
        var requestType = $(this).data('requestType');
        $.post('/managers/requests/confirm-delete-request?type=' + requestType + '&request_id=' + requestId, function(data) {});
    });
    
    /*
     * Очистить форму заполнения заявки, если пользователь нажал в модальном окне "Отмена"
     */ 
    $('.request__btn_close').on('click', function() {
        $('#create-new-request')[0].reset();
        $('.field-modal-textarea span').each(function(){
            $(this).text('');
        });        
    });
    
    /*
     * Запрос на отключение статуса у заявки
     */
    $(document).on('click', '#close-chat', function() {
        var requestID = $(this).data('request');
        $.post('/managers/requests/confirm-close-chat?request_id=' + requestID, function(data) {});
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
        
        $.post('/managers/news/for-whom-news?status=' + forWhom,
            function(data) {
                $('#adress_list').html(data);
            }
        );
    });
    
    /*
     * Блокируем виды оповещений, если выбран пункт "Публикация в личном кабинете"
     */
    $('#type_notice').on('change', function (){
        var valueType = $('input[name*=isPrivateOffice]:checked').val();

        if (valueType === '0') {
            $('input[id^=is_notice]').prop('disabled', true);
            $('input[id^=is_notice]').prop('checked', false);
        } else {
            $('input[id^=is_notice]').prop('disabled', false);
            $('input[id^=is_notice]').prop('checked', false);            
        }
    });
    
    /*
     * Перед загрузкой модального окна на удаление новости, присваиваем в дата атрибут ID новости
     */
    $('#delete_news_manager').on('show.bs.modal', function(e) {
        var newsId = $(e.relatedTarget).data('news');
        $('.delete_news__del').data('news', newsId);
    });

    /*
     * Запрос на удаление новости
     */
    $('.delete_news__del').on('click', function(){
        var newsId = $(this).data('news');
        
        $.ajax({
            url: '/managers/news/delete-news',
            method: 'POST',
            data: {
                newsId: newsId,
            },
            success: function(responce){
//                console.log(responce.success);
            },
            error: function(){
                console.log('error');
            },
        });
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
        $.post('/managers/news/delete-file?file=' + fileId, function(data) {
            $('a[id^=delete-file-yes-' + fileId + ']').closest('tr').remove();
            e.preventDefault(); 
        });
    });
    
    $(document).on('click' , 'a[id^=delete-file-no]', function(e){
        var fileId = $(this).data('files');
        $('#delete-file-no-' + fileId).parent().html('&times;');
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

    // ******************************************************** //
    // ************     Start Block of Voting    ************** //
    // ******************************************************** //
    /*
     * Переключатель, тип голосования
     *      Для всех
     *      Для конкретного дома
     */
    $('#for_whom_voting').on('change', function(e) {
        var forWhom = $("#form-voting input[type='radio']:checked").val();
        
        if (forWhom === '0') {
            $('#house-lists').prop('disabled', true);
        } else {
            $('#house-lists').prop('disabled', false);
        }
        
        $.post('/managers/voting/for-whom-news?status=' + forWhom,
            function(data) {
                $('#house-lists').html(data);
            }
        );
    });    
    
    /*
     * Предзагрузка модального окна с запросом на удаление записи голосования
     */
    $('#delete_voting_manager').on('show.bs.modal', function(e){
        var votingId = $(e.relatedTarget).data('voting');
        $('.delete_voting__del').data('voting', votingId);
    });
    
    /*
     * Запрос на удаление голосования
     */
    $('.delete_voting__del').on('click', function(){
        var votingId = $(this).data('voting');
        $.ajax({
            url: '/managers/voting/confirm-delete-voting',
            method: 'POST',
            data: {
                votingId: votingId,
            },
            success: function (response){
                return console.log(response.success);
            },
            error: function (){
                return console.log('error');                
            },
        })
    });
    
    /*
     * Запрос на подтверждение закрытия голосования
     */
    var messageText;
    $('.close_voting_btn').on('click', function(e){
        var votingId = $(this).data('voting');
        $.ajax({
            url: '/managers/voting/confirm-close-voting',
            method: 'POST',
            data: {
                votingId: votingId,
            },
            success: function(response) {
                if (response.success === true && response.close === 'ask') {
                    messageText = response.message + ' <b>' + response.title + '</b>?';
                } else if (response.success === true && response.close === 'yes') {
                    messageText = response.message + ' <b>' + response.title + '</b>?';
                }
                $('#close_voting_manager .close_voting_yes').data('voting', votingId);
                $('#close_voting_manager').modal('show');
            },
            error: function(){},
        });
    });
    
    /*
     * Закрытие голосования
     */
    $('.close_voting_yes').on('click', function(e){
        e.preventDefault();
        var votingId = $(this).data('voting');
        $.ajax({
            url: '/managers/voting/close-voting',
            method: 'POST',
            data: {
                votingId: votingId,
            },
            error: function (jqXHR, textStatus, errorThrown) {},
        });
        return false;
    });
    
    $('#close_voting_manager').on('show.bs.modal', function(e){
        $(this).find('.modal__text p').html(messageText);
    });   
    
    /*
     * Загрузка профиля пользователя в модальном окне
     */
    $('a#view-profile').on('click', function(e){
        var link = $(this).attr('href');
        $('#view-user-profile').modal('show');
        $('#view-user-profile .modal-content .modal-body').load(link);
        e.preventDefault();
        return false;        
    });
    
    
    // ******************************************************** //
    // ************     Start Block of Estates   ************** //
    // ******************************************************** //    
    
    /*
     * Метод загрузки модального окна на редактирование описания о доме
     */
    $('a#edit-discription-btn').on('click', function(e){
        var link = $(this).attr('href');
        $('#edit-description-house').modal('show');
        $('#edit-description-house .modal-dialog .modal-content .modal-body').load(link);
        e.preventDefault();
        return false;        
    });
    
    $(document).on('click', '#house_link', function() {
        var house = $(this).attr('href');
        house = house.replace(/[^0-9]/gim, '');
        // Устанавливаем куку, выбранного дома
        setCookie('_house', house);
        
        $.ajax({
            url: '/managers/housing-stock/view-characteristic-house',
            method: 'POST',
            data: {
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

    /*
     * Полное удаление или восстановление выбранной характеристики дома
     */
    // Массив будет содержать клонированные участки кода с выбранными для удаления характеристиками
    var chooseArray = new Array();
    $('#characteristic_list').on('click', '#delete-characteristic__link', function(){
        var html = $(this).closest('tr').html();
        var block = $(this).closest('tr');
        var characteristicId = $(this).data('characteristicId');
        chooseArray.push({'html':html});
        var num = chooseArray.length;

        block.html(
                "<td colspan='2'><span class='rest_char' id='rest_" + num + "'>Восстановить</span> " + 
                "<span class='delete_char' id='char_" + characteristicId + "'>Удалить</span></td>")
        
        $("span[id='rest_" + num + "']").on('click', function () {
            var num = $(this).attr('id');
            num = num.replace(/[^0-9]/gim, '') - 1;
            var parent = $(this).parent();
            parent.html(chooseArray[num]['html']);
        });
        /*
         * Удаление характеристики
         */
        $("span[id='char_" + characteristicId + "']").on('click', function () {
            var charId = $(this).attr('id');
            charId = charId.replace(/[^0-9]/gim, '');
            $.ajax({
                url: '/managers/housing-stock/delete-characteristic',
                method: 'POST',
                data: {
                    charId: charId,
                },
                success: function (data, textStatus, jqXHR) {
                    if (data.success === true) {
                        block.remove();
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(textStatus);
                },
            });
            
        });        
        
    });

    /*
     * Загрузка модального окна для добавления новой характеристики
     */
    $('a#add-charact-btn').on('click', function(e){
        // Перед загрузкой модального окна, проверяем наличие куки выбранного дома
        if (!getCookie('_house')) {
            $('#estate_house_message_manager .modal-title').text(
                    'Ошибка добавления характеристики');
            $('#estate_house_message_manager .modal__text').text(
                    'Для добавления характеристики, пожалуйста, выберите дом из списка "Жилой комплекс" слева');
            $('#estate_house_message_manager').modal('show');
            return false;
        }
        
        var link = $(this).attr('href');
        $('#add-characteristic-modal-form').modal('show');
        $('#add-characteristic-modal-form .modal-dialog .modal-content .modal-body').load(link);
        e.preventDefault();
        return false;        
    });

    /*
     * Загрузка модального окна для загружки документов
     */
    $('a#add-files-btn').on('click', function(e){
        // Перед загрузкой модального окна, проверяем наличие куки выбранного дома
        if (!getCookie('_house')) {
            $('#estate_house_message_manager .modal-title').text(
                    'Ошибка загрузки документа');
            $('#estate_house_message_manager .modal__text').text(
                    'Для загрузки документа, пожалуйста, выберите дом из списка "Жилой комплекс" слева');
            $('#estate_house_message_manager').modal('show');
            return false;
        }
        
        var link = $(this).attr('href');
        $('#add-load-files-modal-form').modal('show');
        $('#add-load-files-modal-form .modal-dialog .modal-content .modal-body').load(link);
        e.preventDefault();
        return false;        
    });
    
    /*
     * Загрузка модального окна для установки статуса "Должник"
     * по чекбоксу "Должник"
     */
    $('#flats_list').on('change', 'input[id ^= check_status__flat-]', function(){
        var flatId = $(this).data('flat');
        var link = '/managers/housing-stock/check-status-flat';
        
        if (!$(this).is(':checked')) {
            $('#estate_note_message_manager').modal('show');
            $('#estate_note_message_manager .estate_note_message__yes').data('flat', flatId);
        }
    });
    
    /*
     * Загрузка модального окна для добавление нового примечания
     * по кнопке "Новое примечание"
     */
    $('#flats_list').on('click', '#add-note', function(){
        var flatId = $(this).data('flat');
        var link = '/managers/housing-stock/check-status-flat';
        $('#add-note-modal-form').modal('show');
        $('#add-note-modal-form .modal-dialog .modal-content .modal-body').load(link, 'flat_id=' + flatId);
    });
    
    $('.estate_note_message__yes').on('click', function() {
        var flatId = $(this).data('flat');
        $.ajax({
            url: '/managers/housing-stock/take-off-status-debtor',
            method: 'POST',
            data: {
                flatId: flatId,
            },
            success: function (response) {
                console.log(response);
                if (response.success) {
                    // Удаляем html блок со всеми примечаниями
                    $('ul[id=note_flat__tr-' + flatId + ']').remove();
                    $('#check_status__flat').attr('checked', false);
                    $('#estate_note_message_manager').modal('hide');
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(textStatus);                
            }
        });
        return false;
    });
    
    /*
     * Удаление примечания для квартиры
     */
    $('#flats_list').on('click', '.flat_note__delete', function(e){
        e.preventDefault();
        var noteId = $(this).data('note');
        var htmlBlock = $(this).closest('li');
        // Количество строк с примечаниями
        var countTr = $('tr[id^=note_flat__tr]').length;
        
        $.ajax({
            url: '/managers/housing-stock/delete-note-flat',
            method: 'POST',
            data: {
                noteId: noteId,
            },
            success: function (responce, textStatus, jqXHR) {
                if (responce.success === true) {
                    htmlBlock.remove();
                }
                // Если количество примечаний <=1 + Строка заголовок с кнопкой "Добавить примечание", то снимаем с квартиры статус Должник
                if (countTr <= 2) {
                    $('#add-note').closest('tr').remove();
                    $('#check_status__flat').attr('checked', false);
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(textStatus);                
            }
        });
        return false;
    });
    
    /*
     * Удаление прикрепленного документа
     */
    $('#files_list').on('click', '#delete_file__house', function(e) {
        var blockHtml = $(this).closest('tr');
        var fileId = $(this).data('files');
        e.preventDefault();
        $.ajax({
            url: '/managers/housing-stock/delete-files-house',
            method: 'POST',
            data: {
                fileId: fileId,
            },
            success: function (response) {
                if (response.success === true){
                    blockHtml.remove();
                } else if (response.success === false) {
                    blockHtml.html('Ошибка удаления документа');
                }

            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(textStatus);
            },
        });
        return false;
    });
    
    // ******************************************************** //
    // ************     Start Block of Designer   ************** //
    // ******************************************************** // 
    /*
     * Переключение списка Заявок, разд "Заявки"
     * Переключение списка Категорий, разд "Платные услуги"
     */
    $('.requests-list > li, .categories-list > li').on('click', function() {
        var recordID = $(this).data('record');
        var typeRecord = $(this).data('recordType');
        $('.requests-list > li, .categories-list > li').removeClass('active-item');
        $(this).addClass('active-item');
        $.post('/managers/designer-requests/show-results?type_record=' + typeRecord + '&record_id=' + recordID, function(response) {
            if (response.success === true) {
                $('#block__lists-services').html(response.data);
            }
        });
    });
    
    /*
     * Запрос на удаление записи
     */
    var recordId, recordType;
    $(document).on('click', '.category__delete, #service__delete, .request__delete, #question__delete', function() {
        recordId = $(this).data('record');
        recordType = $(this).data('recordType');
        $('#designer-confirm-message').modal('show');
    });
    
    $('#designer-confirm-message').on('show.bs.modal', function(e) {
        
        if (recordType === 'category') {
            $(this).find('.modal-confirm').html(
                    'Вы действительно хотите удалить выбранную ватегорию? Все принадлежащие выбранной категории услуги будут также удалены. Продолжить?');
        } else if (recordType === 'service') {
            $(this).find('.modal-confirm').html(
                    'Вы действительно хотите удалить выбранную услугу?');
        } else if (recordType === 'request') {
            $(this).find('.modal-confirm').html(
                    'Вы действительно хотите удалить выбранную заявку?');
        } else if (recordType === 'question') {
            $(this).find('.modal-confirm').html(
                    'Вы действительно хотите удалить выбранный вопрос?');
        }

        $(this).find('.delete_record__des').data('record', recordId);
        $(this).find('.delete_record__des').data('recordType', recordType);
        
    });
    
    $('.delete_record__des').on('click', function() {
        $.post('/managers/designer-requests/delete-record?type=' + recordType + '&record_id=' + recordId, function(response) {});
    });
    
    $(document).on('click', 'a.edit-service-btn', function(e) {
        var link = $(this).attr('href');
        $('#edit-service-modal-form').modal('show');
        $('#edit-service-modal-form .modal-dialog .modal-content .modal-body').load(link);
        e.preventDefault();
        return false;        
    });

    $(document).on('click', 'a.edit-question-btn', function(e) {
        var link = $(this).attr('href');
        $('#edit-question-modal-form').modal('show');
        $('#edit-question-modal-form .modal-dialog .modal-content .modal-body').load(link);
        e.preventDefault();
        return false;        
    });
    
    function searchDesignerForm() {
        var input, filter, ul, li, a, i, txtValue;
        input = document.getElementById('search-input-designer');
        filter = input.value.toUpperCase();
        ul = document.getElementById('search-lists');
        li = ul.getElementsByTagName('li');
        for (i = 0; i < li.length; i++) {
            a = li[i].getElementsByTagName('p')[0];
            txtValue = a.textContent || a.innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                li[i].style.display = '';
            } else {
                li[i].style.display = 'none';
            }
        }
    }
    $('#search-input-designer').on('input', function(){
        searchDesignerForm();
    });
    
    /*
     * Сброс форм
     */
    $('.modal').on('hide.bs.modal', function () {
        if ($('form').length > 0) {
            $('form')[0].reset();
            $(this).find('.help-block').text('');
        }
    });
    
    
    // ******************************************************** //
    // ************     Start Block of Settings  ************** //
    // ******************************************************** //
    
    $('.delete-item-settings').on('click', function() {
        var recordId = $(this).data('record');
        var recordType = $(this).data('type');
        $.post('delete-record?item=' + recordId + '&type=' + recordType, function(response) {
//            console.log(response);
        });
    });
    
    /*
     * Переключение статуса слайдера
     */
    $('button[id^=switch-status-]').on('click', function() {
        var sliderId = $(this).data('record');
        var button = $(this);
        $.post('switch-status-slider?item=' + sliderId, function(response) {
            button.toggleClass('__slider-off');
        });
    });
    
    /*
     * Редактирование записи Партнера
     */
    $(document).on('click', 'a.edit-partner-btn', function(e) {
        var link = $(this).attr('href');
        $('#edit-partner-modal-form').modal('show');
        $('#edit-partner-modal-form .modal-dialog .modal-content .modal-body').load(link);
        e.preventDefault();
        return false;        
    });
    
    

    /*
     * Установка куки
     */
    function setCookie(name, value, expire){
        var date = new Date(new Date().getTime() + (expire * 1000 * 60 * 60 * 24)).toUTCString();
        document.cookie = name + '=' + value + '; path=/; expires=' + date;
    }

    function getCookie(name) {
      var matches = document.cookie.match(new RegExp(
        "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
      ));
      return matches ? decodeURIComponent(matches[1]) : undefined;
    } 
    
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

    /*
     * Фильтр контента по адресу дома, для навигационной панели
     */
    function filterByHouseAdress(house_id) {
        $.post('filter-by-house-adress?house_id=' + house_id, function(response) {
            if (response.success == true) {
                $('#_list-res').html(response.data);
            } else if (response.success == false) {
                $('#_list-res').html('');
            }
        }); 
    }

    /* Кастомизация элеметнов управления формой, список адресов в навигационном меню */
    $(".custom-select-services").each(function() {
        var classes = $(this).attr("class"),
            id = $(this).attr("id"),
            name = $(this).attr("name");
        var template =  '<div class="' + classes + '">';
            template += '<span class="custom-select-trigger-services">' + $(this).attr("placeholder") + '</span>';
            template += '<div class="custom-options-services">';
        // Текущий выбранный лицевой счета    
        var currentValue = $('#sources-services option:selected').val();
        
        $(this).find("option").each(function() {
            var classSelection = ($(this).attr("value") == currentValue) ? 'selection-services ' : '';            
            template += '<span class="custom-option-services ' + classSelection + $(this).attr("class") + '" data-value="' + $(this).attr("value") + '">' + $(this).html() + '</span>';
            
            $(this).val('selection-services');
            
        });
        template += '</div></div>';

        $(this).wrap('<div class="custom-select-wrapper-services"></div>');
        $(this).hide();
        $(this).after(template);
    });

    $(".custom-option-services:first-of-type").hover(function() {
        $(this).parents(".custom-options-services").addClass("option-hover-services");
    }, function() {
        $(this).parents(".custom-options-services").removeClass("option-hover-services");
    });

    $(".custom-select-trigger-services").on("click", function() {
        $('html').one('click',function() {
            $(".custom-select-services").removeClass("opened");
        });
        $(this).parents(".custom-select-services").toggleClass("opened");
        event.stopPropagation();
    });
    
    $(".custom-option-services").on("click", function() {
        var valueSelect = $(this).data("value");
        var textSelect = $(this).text();
        var typePage = $('#sources-adress').data('typePage');
        $(this).parents(".custom-select-wrapper-services").find("select").val(valueSelect);
        $(this).parents(".custom-options-services").find(".custom-option-services").removeClass("selection-services");
        $(this).addClass("selection-services");
        $(this).parents(".custom-select-services").removeClass("opened");
        $(this).parents(".custom-select-services").find(".custom-select-trigger-services").text(textSelect);
        filterByHouseAdress(valueSelect);
    });

    
 });
    



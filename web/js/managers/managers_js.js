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
        $.post('/web/managers/app-managers/show-post?departmentId=' + $(this).val(),
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
            url: '/web/managers/app-managers/block-user-in-view',
            method: 'POST',
            data: {
                userId: userId,
                statusUser: statusUser,
            },
            success: function(response) {
                if (response.status == 2) {
                    $('.block_user').text('Разблокировать');
                    $('.block_user').removeClass('btn-danger');
                    $('.block_user').addClass('btn-success');
                    $('.block_user').data('status', 1);
                } else {
                    if (response.status == 1) {
                        $('.block_user').text('Заблокировать');
                        $('.block_user').addClass('btn-danger');
                        $('.block_user').removeClass('btn-success');
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
    
    /* Обработка события при клике на checkBox 'Арендатор'
     * Если за лицевым счетом закреплен арендатор, то 
     * выводим модальное окно для управления учетной записью арендатора
     */
    $('#is_rent').on('change', function(e) {
        //var rentsId = $('input[id=_rents]').val();
        var accountNumber = $('#_list-account :selected').text();
        var checkShow = $(this).val();
        accountNumber = parseInt(accountNumber, 10);
        // Если на форме есть скрытое поле, содеражащее ID арендатора
        if ($('input').is('#_rents')) {
            // Выводим модальное окно на удаление учетной записи Арендатора
            $('#delete_rent_manager').modal('show');
        } else {
            // Показать форму Добавление нового арендатора
            if ($('#is_rent').is(':checked')) {
//                $.ajax({
//                    url: 'show-form',
//                    method: 'POST',
//                    async: false,
//                    data: {
//                        accountNumber: accountNumber,
//                        _show: checkShow,
//                    },
//                    success: function(response) {
//                        if (response.status && response.show) {
//                            $('.form-add-rent').html(response.data);
//                        } else {
//                            $('.form-add-rent').html(response.message);
//                        }
//                    }
//                });
            } else {
                $('.form-add-rent').html('Арендатор отсутствует');
            }
        }
    });    

    /*
     * Обработка событий в модальном окне 'Дальнейшие действия с учетной записью арендатора'
     * Закрыть модальное окно
     */
    $('.delete_rent__close').on('click', function() {
        $('#is_rent').prop('checked', true);
    });
    
    /*
     * Загрузка данных Арендатора в модальное окно 'Дальнейшие действия с учетной записью арендатора'
     */
    $('#delete_rent_manager').on('show.bs.modal', function(){
        $(this).find('#rent-surname').text($('#rents_surname').data('surname'));
        $(this).find('#rent-name').text($('#rents_name').data('name'));
        $(this).find('#rent-second-name').text($('#rents_second_name').data('second-name'));
    });
    
    // Удалить данные арендатора из системы
    $('.delete_rent__ok').on('click', function() {
        var rentsId = $('input[id=_rents]').val();
        var accountId = $('#_list-account :selected').text();
        $.post({
            url: 'delete-rent-profile?rent=' + rentsId + '&account=' + accountId,
            method: 'POST',
            error: function() {
                console.log('Error #2000-03');
            }
        });
    });
    
    
    /* Обработка события при клике на dropDownList "Список лицевых счетов собственника"
     * Профиль Собственника, блок "Контактные данные арендатора"
     */
    $('#_list-account').on('change', function() {
        var accountNumber = $('input[name*=account-number]');
        var client = $(this).data('client');
        var account = $(this).val();
        
        accountNumber.val($('#_list-account :selected').text());
        $.ajax({
            url: 'check-account',
            data: {
                dataClient: client,
                dataAccount: account,
            },
            error: function() {
                console.log('Error #2000-11');
            },
            dataType: 'json',
            type: 'POST',
            success: function(response) {
                console.log(response.account);
                console.log(response.client);
                if (response.is_rent) {
                    $('#is_rent').prop('checked', true);
                } else {
                    $('#is_rent').prop('checked', false);
                }                
               $("#content-replace").html(response.data);
            }
        });

    });
    
    
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
    $('.delete_empl').on('show.bs.modal', function(e) {
        // Обращаемся к кнопке, которая открыла модальное окно
        var button = $(e.relatedTarget);
        // Получаем ее дата атрибут
        var dataDis = button.data('employer');
        var dataFullName = button.data('fullName');
        $('.delete_empl').find('#disp-fullname').text(dataFullName);
        $(this).find('#confirm_delete-empl').data('employer', dataDis);
    });    

    /*
     * Запрос на удаление профиля сотрудника (Диспетчер)
     */
    $('.delete_disp__del').on('click', function(){
        var employerId = $(this).data('employer');
        $.ajax({
            url: 'query-delete-dispatcher',
            method: 'POST',
            dataType: 'json',
            data: {
                employerId: employerId,
            },
            success: function(response) {
                if (response.isClose === true) {                
                    $('#delete_disp_manager_message').modal('show');
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
     * Запрос на удаление профиля сотрудника (Диспетчер)
     */
    $('.delete_spec__del').on('click', function(){
        var employerId = $(this).data('employer');
        $.ajax({
            url: 'query-delete-specialist',
            method: 'POST',
            dataType: 'json',
            data: {
                employerId: employerId,
            },
            success: function(response) {
                if (response.isClose === true) {                
                    $('#delete_spec_manager_message').modal('show');
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
     * Поиск по Специалистам
     */
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
    $('#category_service').on('change', function(e) {

        $.post('/web/managers/app-managers/show-name-service?categoryId=' + $(this).val(),
        function(data) {
            $('#service_name').html(data);
        });
    });

    /*
     * Поиск собственника по введенному номеру телефона
     * Поиск срабатывает когда поле ввода теряем фокус
     */
    $('body').on('blur', '.mobile_phone', function(e) {
        // Получаем текущее значение
        var strValue = $(this).val();
        // В полученном значении удаляем все символы кроме цифр, знака -, (, )
        strValue = strValue.replace(/[^-0-9,(,)]/gim, '');
        $.post('/web/managers/app-managers/show-houses?phone=' + strValue,
        function(data) {
            $('.house').html(data);
        });
    });
    
    /*
     * Переключение статуса заявки
     */
    $('.switch-request').on('click', function(e){
        e.preventDefault();
        var linkValue = $(this).text();
        var statusId = $(this).data('status');
        var requestId = $(this).data('request');
        var liChoosing = $('li#status' + statusId);
        $.ajax({
            url: 'switch-status-request',
            method: 'POST',
            data: {
                statusId: statusId,
                requestId: requestId,
            },
            success: function(response) {
                if (response.status) {
                    console.log(response.status);
                    $('.dropdown-menu').find('.disabled').removeClass('disabled');
                    $('#value-btn').text(linkValue);
                    liChoosing.addClass('disabled');
                    if (statusId === 4) {
                        $('.btn:not(.dropdown-toggle)').attr('disabled', true);
                    } else {
                        $('.btn:not(.dropdown-toggle)').attr('disabled', false);
                    }
                }
            },
            error: function() {
                console.log('error');
            },
        });
    });

    /*
     * Переключение статуса заявки на платную услугу
     */
    $('.switch-paid-request').on('click', function(e){
        e.preventDefault();
        var linkValue = $(this).text();
        var statusId = $(this).data('status');
        var requestPaidId = $(this).data('request');
        var liChoosing = $('li#status' + statusId);
        $.ajax({
            url: 'switch-status-paid-request',
            method: 'POST',
            data: {
                statusId: statusId,
                requestPaidId: requestPaidId,
            },
            success: function(response) {
                if (response.status) {
                    $('.dropdown-menu').find('.disabled').removeClass('disabled');
                    $('#value-btn').text(linkValue);
                    liChoosing.addClass('disabled');
                    if (statusId === 4) {
                        $('.btn:not(.dropdown-toggle)').attr('disabled', true);
                        $('textarea[id="comments-text"]').attr('disabled', true);
                    } else {
                        $('.btn:not(.dropdown-toggle)').attr('disabled', false);
                        $('textarea[id="comments-text"]').attr('disabled', false);                        
                    }
                }
            },
            error: function() {
                console.log('error');
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
                url: 'choose-dispatcher',
                method: 'POST',
                data: {
                    dispatcherId: dispatcherId,
                    requestId: requestId,
                    typeRequest: typeRequest,
                },
                success: function(response) {
                    console.log(response.type_request);
                    if (response.success === false) {
                        $('.error-message').text('Ошибка');
                        return false;
                    }
                    
                    $('.btn-dispatcher').data('employee', dispatcherId);
                    $('#dispatcher-name').text('');
                    $('#dispatcher-name').html(
                            '<a href="/web/managers/employers/edit-dispatcher?dispatcher_id=' + dispatcherId + '">' + 
                            employeeName + '</a>');
                },
                error: function() {
                    $('.error-message').text('Ошибка');
                },
            });
        }
    });

    
    /*
     * Отправляем запрос на добавления диспетчера к выбранной заявке
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
                    $('#specialist-name').html(
                            '<a href="/web/managers/employers/edit-specialist?specialist_id=' + specialistId + '">' + 
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
        
        $.post('for-whom-news?status=' + forWhom,
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
        var isAdvert = $(e.relatedTarget).data('isAdvert');
        $('.delete_news__del').data('news', newsId);
        $('.delete_news__del').data('isAdvert', isAdvert);
    });

    /*
     * Запрос на удаление новости
     */
    $('.delete_news__del').on('click', function(){
        var newsId = $(this).data('news');
        var isAdvert = $(this).data('isAdvert');
        
        $.ajax({
            url: '/web/managers/news/delete-news',
            method: 'POST',
            data: {
                newsId: newsId,
                isAdvert: isAdvert,
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
    $('.delete_file').on('click', function(){
       var fileId = $(this).data('files');
       alert(fileId);
       $.ajax({
           url: 'delete-file',
           method: 'POST',
           data: {
               fileId: fileId,
           },
           success: function(response){
//               console.log(response.status);
           },
           error: function(){
               console.log('error');
           },
       });
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
     *      Для дома
     *      Для подъезда
     */
    $('#type_voting').on('change', function(e) {
        var objectType = $("#create-voting input[type='radio']:checked").val();
//        if (forWhom === '0') {
//            $('#adress_list').prop('disabled', true);
//        } else {
//            $('#adress_list').prop('disabled', false);
//        }
        $.post('for-whom-voting?status=' + objectType,
            function(data) {
                $('#object_vote_list').html(data);
            }
        );
    });    
    
});
    



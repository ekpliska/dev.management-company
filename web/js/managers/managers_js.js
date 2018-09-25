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

    $('body').on('blur', '.mobile_phone', function(e) {
        var strValue = $(this).val();
        strValue = strValue.replace(/[^-0-9,(,)]/gim, '');
        $.post('/web/managers/app-managers/show-houses?phone=' + strValue,
        function(data) {
            $('.house').html(data);
        });
    });

});
    



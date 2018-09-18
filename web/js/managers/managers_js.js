/* 
 * For Managers of Modules
 */

$(document).ready(function() {
    
    // ******************************************************** //
    // ************    Start Block of Profile    ************** //
    // ******************************************************** //
    // /module/controller/action
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
        var statusClient = $(this).data('status');        
        $.ajax({
            url: 'block-client-in-view',
            method: 'POST',
            data: {
                userId: userId,
                statusClient: statusClient,
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

    

});
    



/* 
 * For Clients of Modules
 */

$(document).ready(function() {
    
    // ******************************************************** //
    // ************    Start Block of Profile    ************** //
    // ******************************************************** //

    /* Обработка события при клике на checkBox 'Арендатор'
     * Если за лицевым счетом закреплен арендатор, то 
     * выводим модальное окно для управления учетной записью арендатора
     */
    $('#is_rent').change(function(e) {
        var rentsId = $('input[id=_rents]').val();
        if ($('input').is('#_rents')) {
            $('#changes_rent').modal('show');
            $.ajax({
                url: 'get-rent-info?rent=' + rentsId,
                method: 'POST',
                dataType: 'json',
                data: {
                    rent_id: rentsId,
                },
                success: function(response) {
                    if (response.status) {
                        $('#changes_rent #rent-surname').text(response.rent.rents_surname);
                        $('#changes_rent #rent-name').text(response.rent.rents_name);
                        $('#changes_rent #rent-second-name').text(response.rent.rents_second_name);
                    } else {
                        console.log('Error #1000-01');
                    }
                }
            });
        } else {
            $('#add-rent-modal').modal('show');
        }
    });
    
    /* Обработка события при клике на dropDownList "Список лицевых счетов собственника"
     * Профиль, блок "Контактные данные арендатора"
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
                console.log('Error #1000-11');
            },
            dataType: 'json',
            type: 'POST',
            success: function(response) {
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
     * Подтверждение удаления арендатора 
     */
    $('#confirm-delete').on('click', '.btn-ok-delete', function(e) {
        var id = $(this).data('recordId');
        $.ajax({url: 'change-rent-profile?action=delete&rent=' + id})
    });
    
    $('#confirm-delete').on('show.bs.modal', function(e) {
        var data = $(e.relatedTarget).data();
        $('.title', this).text(data.recordFullname);
        $('.btn-ok-delete', this).data('recordId', data.recordId);
    });


    /*
     * Объединение арендатора с лицевым счетом
     */
    $('#bind-rent-modal').on('click', '.btn-ok-bind', function(e) {
        var rentId = $(this).data('rent');
        var accountId = $('#_list-account-rent :selected').text();
        console.log(rentId + ' ' + accountId);
        $.ajax({
            url: 'change-rent-profile?action=bind&rent=' + rentId + '&account=' + accountId,
            method: 'GET',
            success: function(response) {
                // console.log('Объединение арендатора с лицевым счетом OK');
            },
            error: function() {
                console.log('Error #1000-02');
            }
        })
    });
    
    /*
     * Перед загрузкой модального окна подтвержадения удаления арендатора
     * получаем data параметры
     */ 
    $('#bind-rent-modal').on('show.bs.modal', function(e) {
        var data = $(e.relatedTarget).data();
        $('.fullname', this).text(data.rentFullname);
        $('.btn-ok-bind', this).data('rent', data.rent);
    });

    /*
     * Обработка событий в модальном окне 'Дальнейшие действия с учетной записью арендатора'
     */
    // Закрыть модальное окно
    $('.changes_rent__close').on('click', function() {
        $('#is_rent').prop('checked', true);
    });
    
    // Удалить данные арендатора из системы
    $('.changes_rent__del').on('click', function() {
    
        var rentsId = $('input[id=_rents]').val();
        var accountId = $('#_list-account :selected').text();
        $.get({
            url: 'change-rent-profile?action=delete&rent=' + rentsId + '&account=' + accountId,
            method: 'GET',
            success: function(response) {
                // console.log('Удаление арендатора OK');
            },
            error: function() {
                console.log('Error #1000-03');
            }
        });
    });
    
    // Отвязать арендатора от лицевого счета
    $('.changes_rent__undo').on('click', function() {
    
        var rentsId = $('input[id=_rents]').val();
        var accountId = $('#_list-account :selected').text();
        $.get({
            url: 'change-rent-profile?action=undo&rent=' + rentsId + '&account=' + accountId,
            method: 'GET',
            success: function(response) {
                // console.log('Отвязать арендатора OK');
            },
            error: function() {
                console.log('Error #1000-04');
            }
        });
    });


    /*
     * Сабмит основной формы профиля
     * Перед отправкой проверяем валидацию формы 'Аренадатор'
     */
    $('body').on('beforeSubmit', 'form#profile-form', function (e) {
        e.preventDefault();
        // Получаем данные из формы Арендатор
        var rentForm = $('#edit-rent').serialize();
        // Получаем количество ошибок на форме Арендатор
        var countError = $('#edit-rent').find('.has-error').length;
        
        // Если имеются ошибки и форма Арендатора существует, отправку основной формы профиля останавливаем
        if (countError > 0 && rentForm) {
            return false;
        } else if (countError === 0 && rentForm) {
            $.ajax({
                url: 'save-rent-info',
                data: rentForm,
                method: 'POST',
                typeData: 'json',
                success: function(data) {
                    if (data.status == false) {
                        $('.error-message').text('Заполните поля корректными данными');
                    }
                },
                error: function(data) {
                    console.log('Error #1000-05');
                }
            });
        }
    });

    /*
     * Форма 'Добавить нового Арендатора'
     */
    /*
     * При загрузке модального окна, получаем
     * ID выбранного лицевого счета
     */
    $('#add-rent-modal').on('show.bs.modal', function(e) {
        var accountId = $('#_list-account :selected').text();
        $('#_personal-account').val(accountId);
        $('.btn__add_rent', this).data('accountId', accountId);
    });
    
    // Очистить поля ввода, клик по кнопкам 'Отмена', 'x'
    $('#add-rent-modal .add-rent-modal__close').on('click', function() {
        $('#add-rent input').val('');
        $('#add-rent-modal .modal-body').removeClass('has-error');
        $('#add-rent-modal .modal-body').removeClass('has-success');
        $('#add-rent-modal .form-group').removeClass('has-success'); 
        $('#add-rent-modal .form-group').removeClass('has-error');
        $('#add-rent-modal').find('.help-block').text('');
     });
    
    // Сохраняем нового Арендатора
    $('#add-rent').on('beforeSubmit', function() {
        var addRentForm = $(this);
        $.ajax({
            url: 'add-new-rent',
            type: 'POST',
            data: addRentForm.serializeArray(),
            succeess: function(response) {
                if (response.status === false) {
                    console.log('Error #1000-06-1');
                }
            },
            error: function() {
                console.log('Error #1000-06-2');
            },
        });
    });
   

    /* End Block of Profile */


    // *************************************************************** //
    // ************   Start Block of Personal Account   ************** //
    // *************************************************************** //

    /*
     * Смена информации о лицевом счете,
     * dropDownList _list-account-all
     */    
    $('._list-account-all').on('change', function() {
        var accountId = $(this).val();
        $.ajax({
            url: 'list?account=' + accountId,
            method: 'GET',
            type: 'json',
            success: function(response) {
                $('#account-info').html(response.data);
            },
            error: function() {
                console.log('Error #1000-07');
            },
        });
    });
    
    
    /*
     * Раздел 'Лицевой счет'
     * Форма - Добавить лицевой счет
     */
    // Блокируем список доступных арендаторов, и кнопку добавить арендатора
    $('#list_rent').prop('disabled', true);
    $('.btn__add-rent').prop('disabled', true);
    
    // В зависимости от checkBox 'Арендатор' скрываем/показываем элементы
    $('#isRent').change(function() {
        if (this.checked) {
            $('#list_rent').prop('disabled', false);
            $('.btn__add-rent').prop('disabled', false);
        } else {
            $('#list_rent').prop('disabled', true);
            $('.btn__add-rent').prop('disabled', true);            
        }
    });    
    
    /*
     * Если при создании лицевого счета 
     * арендатор выбран из списка, блокируем кнопку 'Добавить арендатора'
     */
    $('#list_rent').change(function() {
        if (!$(this).val()) {
            $('.btn__add-rent').prop('disabled', false);
        } else {
            $('.btn__add-rent').prop('disabled', true);            
        }
    });

    /*
     * Раздел 'Приборы учета'
     * 
     * 
     * Поля ввода для показания приборов учета блокируем
     */ 
    var ind = $('.indication_val');
    ind.prop('disabled', true);

    /*
     * Если нажата кнопка 'Ввести показания'
     * текстовые поля для ввода показаний делаем доступными
     */
    $('.btn__add_indication').on('click', function() {
        ind.prop('disabled', false);
    });

    /* End Block of Personal Account */


    // ******************************************************* //
    // ************   Start Block of Requests   ************** //
    // ******************************************************* //
    /*
     * Очистить форму заполнения заявки, если пользователь нажал в модальном окне 'Отмена'
     */ 
    $('.request__btn_close').on('click', function () {
        $('#add-request')[0].reset();
    });
    
    /*
     * Сортировка заявок по
     * ID лицевого счета, типу и статусу заявки
     */
    $('.list-group-item').on('click', function(e) {
        e.preventDefault();
        
        var status = $(this).data('status');
        var account_id = $('.current__account_list').val();
        var type_id = $('#account_number').val();
        
        $('.list-group-item').each(function() {
            $(this).removeClass('active');
        });
        $(this).addClass('active');
        
        $.ajax({
            url: 'filter-by-type-request?type_id=' + type_id + '&account_id=' + account_id + '&status=' + status,
            method: 'POST',
            data: {
                type_id: type_id,
                account_id: account_id,
                status: status,
            },
            success: function(data){
                if (data.status === false) {
                    console.log('Error #1000-08');
                } else {
                    $('.grid-view').html(data);
                }
            },
        });        
        
    });
    
    /*
     * Добавление оценки для закрытой заявки
     */
    // Получаем ID заявки через data
    var request_id = $('div#star').data('request');
    // Получаем оценку текущей заявки
    var scoreRequest = $('div#star').data('scoreReguest');
    // Вызываем функцию raty из библиотеки плагина по голосованию
    $('div#star').raty({
        score: scoreRequest, // Оценка
        readOnly: scoreRequest === 0 ? false : true, // Если оценка высталена, то возможность голосования закрываем
        click: function(score) {
            $.ajax({
                url: 'add-score-request',
                method: 'POST',
                data: {
                    score: score,
                    request_id: request_id,
                },
                success: function(response) {
                    if (response.status === true) {
                        $('#score-modal-message').modal('show');
                        $('div#star').raty({
                            score: score,
                            readOnly: true,
                        });
                    }
                },
                error: function() {
                    console.log('Error #1000-09');
                }
            });
        }
    });    
    
    /*
     * Всплывающая подсказка для кнопки "Вам доступна система оценки"
     */
    $('[data-toggle="popover"]').popover();
    
    
    /* End Block of Requests */

    // ************************************************************ //
    // ************   Start Block of Paid Services   ************** //
    // *********************************************************** //
    
    /*
     * Загрузка модального окна "Добавить платную услугу"
     * В dropDownList, hiddenInput загружаем ID выбранной услуги
     */
    $(".new-rec").on("click", function(){
        var idService = $(this).data("record");
        $("#add-record-modal").modal("show");
        $("#add-record-modal").find("#name_services").val(idService);
        $("#secret").val(idService);
    });    


    /*
     * Сбросить заполненные поля формы в случае, 
     * если модальное окно "Добавить платную услугу" закрыто через кнопку "Отмена" / x
     */
    $('.btn__paid_service_close').on('click', function() {
        $('#add-paid-service')[0].reset();
    });

    /*
     * Поиск по исполнителю
     */
    $('#_search-input').on('input', function() {
    
        var searchValue = $(this).val();
        var accountId = $('.current__account_list').val();
        
        $.ajax({
            url: 'search-by-specialist',
            method: 'POST',
            data: {
                searchValue: searchValue,
                accountId: accountId,
            },
            success: function(response) {
                $('.grid-view').html(response.data);
            },
            error: function() {
                console.log('Error #1000-10');
            }
        });
    });

    /* End Block of Paid Services */

});

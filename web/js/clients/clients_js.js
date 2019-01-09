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
    
    
    $('#is_rent').on('change', function() {
        
        var currentAccount = $('.custom-options').find('.selection').data('value');
        accountNumber = parseInt(currentAccount, 10);
        $.post('check-is-rent?account=' + accountNumber, function(response) {
            if (response.is_rent === false) {
                $('#add-rent-modal').modal('show');
            } else if (response.is_rent === true) {
                $('#changes_rent').modal('show');
            }
        });
        return false;
    });    
    
    
    $('input[name*=account-number]').val($('.current__account_list :selected').text());
    
    /*
     * Функция смены текущего лицевого счета,
     * в личном кабинете пользователя
     * @param {integer} currentValue ID текущего лицевого счета
     * @param {integer} valueSelect ID выбранного лицевого счета
     */
    function switchAccountNumber(currentValue, valueSelect) {
        $.ajax({
            url: 'check-account',
            data: {
                currentAccount: currentValue,
                newCurrentAccount: valueSelect,
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
            },
            error: function() {
                console.log('Error #1000-11');
            }
        });        
    }
    
    /*
     * Снять чекбокс Арендатор, если пользователь закрыл модальное окно "Новый арендатор"
     */
    $('.add-rent-modal__close').on('click', function() {
        $('#is_rent').prop('checked', false);
    });    

    /*
     * Обработка событий в модальном окне 'Дальнейшие действия с учетной записью арендатора'
     * Закрыть модальное окно
     */
    $('.changes_rent__close').on('click', function() {
        $('#is_rent').prop('checked', true);
    });
    
    // Удалить данные арендатора из системы
    $('.changes_rent__del').on('click', function() {
        var rentsId = $('input[id=_rents]').val();
        $.ajax({
            url: 'delete-rent-profile',
            method: 'POST',
            data: {
                rentsId: rentsId,
            },
            success: function (response, textStatus, jqXHR) {
//                console.log(textStatus);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log('Error #1000-03');                
            },
        });
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
    
    $('.rent-info__btn_close').on('click', function () {
        $('#is_rent').prop('checked', false);
        $('#create-rent-form')[0].reset();
    });
    
    /*
     * Смена номера мобильного телефона,
     * Снять атрибут, только для чтения
     * Кастомизация текстового поля
     */
    $('.settings-input-phone').on('click', function(){
        $(this).prop('readOnly', false);
    });

    $('.settings-input-phone').on('blur', function(){
        $(this).prop('readOnly', true);
    });
    
    $('.phone-inp').on('input', function(){
        $('#change-phone-form button').removeClass('change-record-btn');
    });

    $('.email-inp').on('input', function(){
        $('#change-email-form button').removeClass('change-record-btn');
    });    
    
    /* End Block of Profile */


    // *************************************************************** //
    // ************   Start Block of Personal Account   ************** //
    // *************************************************************** //

    /*
     * Раздел "Лицевой счет"
     * Форма - Добавить лицевой счет
     */
    // Блокируем кнопку добавить арендатора
    $('.btn__add-rent').prop('disabled', true);
    
    // В зависимости от checkBox 'Арендатор' скрываем/показываем элементы
    $('#isRent').change(function() {
        if (this.checked) {
            $('.btn__add-rent').prop('disabled', false);
        } else {
            $('.btn__add-rent').prop('disabled', true);            
        }
    });
    
//    /*
//     * Если в модальном окне "Новый арендатор" нажата нопка Отмена/х
//     * Сбрасываем заполненный поля
//     * Снимаем чекбокс Арендатор
//     * Блокирем кнопку "Добавить арендатора"
//     * 
//     */
//    $("#add-rent-modal .btn__modal_rent_close, .btn__modal_close").on("click", function() {
//        $("#add-rent-modal input").val("");
//        $("#add-rent-modal .modal-body").removeClass("has-error");
//        $("#add-rent-modal .modal-body").removeClass("has-success");
//        $("#add-rent-modal .form-group").removeClass("has-success"); 
//        $("#add-rent-modal .form-group").removeClass("has-error");
//        $("#add-rent-modal").find(".help-block").text("");
//        $("#isRent").prop("checked", false);
//        $(".btn__add-rent").prop("disabled", true);
//    });
//    
//    /*
//     * Метод контрольной проверки заполнения формы "Новый лицевой счет"
//     * до того, как была нажала кнопка "Добавить лицевой счет"
//     */
//    $("#add-account").on("beforeSubmit.yii", function (e) {
//
//        // Форма "Новый арендатор" по умолчанию считается не заполненной
//        var isCheck = false;
//        // Поиск в модальном окне создания арендатора поля для заполнения
//        var form = $("#add-rent-modal").find("input[id*=clientsrentform]");
//        // Количество полей на форме "Новый арендатор"
//        var field = [];
//
//        // Проверяем каждое поле на форме "Новый арендатор" на заполнение
//        form.each(function() {
//            field.push("input[id*=clientsrentform]");
//            var value = $(this).val();
//            // console.log(value);
//            for (var i = 1; i < field.length; i++) {
//                // Если втречается заполненное поле, то статус заполнения формы меням на положительный
//                if (value) {
//                    isCheck = true;
//                }
//            }
//            // console.log(field.length);
//        });
//    
//        /*
//        *   Перед отправкой формы, проверяем чекбокс "Арендатор"
//        *   Если переключатель установлен, то проверяем наличие выбранного арендатора из списка
//        *   или наличее добавленного арендатора
//        */
//        if (!$("#isRent").is(":checked")) {
//            $("#add-rent-modal input").val("");
//            $("#add-rent-modal .modal-body").removeClass("has-error");
//            $("#add-rent-modal .modal-body").removeClass("has-success");
//            $("#add-rent-modal .form-group").removeClass("has-success"); 
//            $("#add-rent-modal .form-group").removeClass("has-error");
//            $("#add-rent-modal").find(".help-block").text("");
//            $("#isRent").prop("checked", false);
//            $(".btn__add-rent").prop("disabled", true);
//            e.preventDefault();    
//        }
//    });
    
    /*
     * Сброс формы в модальном окне на добавление нового лицевого счета при закрытии формы "Отмена"/х
     */
    $('.account-create__btn_close').on('click', function () {
        $('#create-account-modal-form')[0].reset();
    }); 
    
    /*
     * Список квитанций, переключение по квитанции для загрузки PDF
     */
    $(document).on('click', '.list-group-item', function() {
        var liItem = $(this).data('receipt');
        var accountNumber = $(this).data('account');
        var url = location.origin + '/web/receipts/' + accountNumber + '/' + accountNumber + '-' + liItem + '.pdf';
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
     * Общий метод формирования AJAX запросов для раздела Лицевой счет
     * @param {type} accountNumber Лицевой счет
     * @param {type} startDate Дата начала даипазона
     * @param {type} endDate Дата конца диапазона
     * @param {type} idContent ID блока, в котором будет выведен результат запроса
     * @param {type} type Тип запроса (Квитанции, Платежи, Приборы учета)
     */
    function getDataClients (accountNumber, startDate, endDate, idContent, type) {
        var parseStartDate = dateParse(startDate);
        var parseEndDate = dateParse(endDate);
        
        if (parseStartDate - parseEndDate > 0) {
            $('.message-block').addClass('invalid-message-show').html('Вы указали некорректный диапазон');
        } else if (parseStartDate - parseEndDate <= 0) {
            $('.message-block').removeClass('invalid-message-show').html('');
            $.post('search-data-on-period?account_number=' + accountNumber + '&date_start=' + startDate + '&date_end=' + endDate + '&type=' + type,
                function(data) {
                        console.log(data.data_render);
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
        var accountNumber = $(this).data('accountNumber');
        var startDate = $('input[name="date_start-period"]').val();
        var endDate = $('input[name="date_end-period"]').val();
        
        getDataClients(accountNumber, startDate, endDate, '#receipts-lists', 'receipts');
        
    });
    
    /*
     * Запрос на получение списка платежей в заданный диапазон
     */
    $('#btn-show-payment').on('click', function(){
        var accountNumber = $(this).data('accountNumber');
        var startDate = $('input[name="date_start-period-pay"]').val();
        var endDate = $('input[name="date_end-period-pay"]').val();
        console.log(accountNumber);
        getDataClients(accountNumber, startDate, endDate, '#payments-lists', 'payments');
        
    });    
    
    /*
     * Раздел 'Приборы учета'
     * Поля ввода для показания приборов учета блокируем
     */ 
    var ind = $('.indication_val');
    ind.prop('disabled', true);
    /*
     * Если нажата кнопка 'Ввести показания'
     * текстовые поля для ввода показаний делаем доступными
     */
    $('.btn-edit-reading').on('click', function() {
        $(this).prop('disabled', true);
        ind.prop('disabled', false);
        ind.first().focus();
        $('.btn-save-reading').prop('disabled', false);
    });
    
    /*
     * Формирование автоматической заявки на платную услугу
     * "Поверка приборов учета"
     */
    $('.create-send-request').on('click', function() {
        var accountID = $(this).data('account');
        var typeCounter = $(this).data('counterType');
        var numCounter = $(this).data('counterNum');
        
        console.log(accountID + ' ' + typeCounter + ' ' + numCounter);
        $.ajax({
            url: 'create-paid-request',
            method: 'POST',
            data: {
                accountID: accountID,
                typeCounter: typeCounter,
                numCounter: numCounter,
            },
            success: function (data) {
                if (data.success == true) {
                    $('.create-send-request').replaceWith('<span class="message-request">Сформирована заявка ID' + data.request_number + '</span>');
                }
            },
            error: function (textStatus) {
                console.log(textStatus);                
            }
        });
    });
    
    var month = [
        'январь',
        'февраль',
        'март',
        'апрель',
        'май',
        'июнь',
        'июль',
        'август',
        'сентябрь',
        'октябрь',
        'ноябрь',
        'декабрь',
    ];
    
    /*
     * Запрос на формирование предыдущих показаний приборов учета
     */
    $('#date_start-period-counter').on('change', function() {
        var dateValue = $(this).val();
        var numMonth = dateValue.split('-')[0];
        var year = dateValue.split('-')[1];
        var monthNumber = month.indexOf(numMonth.toLowerCase()) + 1;
        console.log(monthNumber + ' ' + year);
        
        $.post('find-indications?month=' + monthNumber + '&year=' + year, function(response) {
            $('#indication-table').html(response.result);
            console.log(response.result);
        });
    });
    
    /* End Block of Personal Account */


    // ******************************************************* //
    // ************   Start Block of Requests   ************** //
    // ******************************************************* //
    /*
     * Очистить форму заполнения заявки, если пользователь нажал в модальном окне 'Отмена'
     */ 
    $('.request__btn_close').on('click', function () {
        $('#add-request-modal-form')[0].reset();
        $('.field-modal-textarea span').each(function(){
            $(this).text('');
        });        
    });
    
    /*
     * Сортировка заявок по
     * ID лицевого счета и статусу заявки
     */
    $('.status_request-switch').on('click', function(e) {
        e.preventDefault();
        
        var status = $(this).data('status');
        
        $('.status_request-switch').each(function() {
            $(this).removeClass('active');
        });
        $(this).addClass('active');
        
        $.ajax({
            url: 'filter-by-type-request?status=' + status,
            method: 'POST',
            data: {
                status: status,
            },
            success: function(data) {
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
    
    
    /* End Block of Requests */

    // ************************************************************ //
    // ************   Start Block of Paid Services   ************** //
    // *********************************************************** //
    
    /*
     * Загрузка модального окна "Добавить платную услугу"
     * В dropDownList, hiddenInput загружаем ID выбранной услуги
     */
    $(document).on('click', '.new-rec', function(){
        var idService = $(this).data('service');
        var idCategory = $(this).data('service-cat');
        $('#add-record-modal').modal('show');
        $('#add-record-modal').find('#name_services').val(idService);
        $('#secret-name').val(idService);
        $('#secret-cat').val(idCategory);
        $('#name_services').val(idCategory);
    }); 

    /*
     * Сбросить заполненные поля формы в случае, 
     * если модальное окно "Добавить платную услугу" закрыто через кнопку "Отмена" / x
     */
    $('.btn__paid_service_close').on('click', function() {
        $('#add-paid-service')[0].reset();
        $('.field-modal-textarea span').each(function(){
            $(this).text('');
        });
    });

    /*
     * Поиск по исполнителю
     */
    $('#_search-input').on('input', function() {
    
        var searchValue = $(this).val();
        
        $.ajax({
            url: 'search-by-specialist',
            method: 'POST',
            data: {
                searchValue: searchValue,
            },
            success: function(response) {
                $('.grid-view').html(response.data);
            },
            error: function() {
                console.log('Error #1000-10');
            }
        });
    });
    
    /*
     * Фильтр заявок по категориям
     */
    function filterServicesCategory(categoryId) {

        $.post('filter-category-services?category=' + categoryId, function(response) {
            $('#services-list').html(response.data);                
        });         
        
    }
    
    /*
     * Счетчик подстчета количества вводимых символов в поле комментарий к заявке
     */
    limitLettrs('.comment', 250, '#label-count', '#label-count-left');
    limitLettrs('#commentstorequest-comments_text', 250, '#label-count', '#label-count-left');

    /* End Block of Paid Services */


    // ************************************************************ //
    // ***************   Start Block of Voting    **************** //
    // *********************************************************** //

    /*
     * Отмена участия в голосовании
     */
    $('#renouncement_participate, #renouncement_participate_close').on('click', function(){
        var votingId = $(this).data('voting');
        $.ajax({
            type: 'POST',
            url: 'renouncement-to-participate',
            data: { votingId: votingId, }
        }).done(function(response) {
//            console.log('voting');
        });
    });
    
    /*
     * Повторная отправка СМС кода
     */
    $('#repeat_sms_code').on('click', function(){
        var votingId = $(this).data('voting');
        $.ajax({
            type: 'POST',
            url: 'repeat-sms-code',
            data: { votingId: votingId, }
        }).done(function(response) {
            if (response.success === true) {
                $('.repeat_sms_code-message').text('Новый код был отправлен');
            } else if (condition) {
                $('.repeat_sms_code-message').text('Ошибка отправки СМС сообщения');                
            }
        });
        return false;
    });
    
    
    /* End Block of Voting */
    

    // ************************************************************ //
    // ***************     Work with send SMS     **************** //
    // *********************************************************** //

    // Количество секунд до следующей отправки
    var timeMinute = 60*2;
    
    // Таймер
    function startTimer(duration, display) {
        var timer = duration, minutes, seconds;
        var startTimer = setInterval(function () {
            minutes = parseInt(timer / 60, 10)
            seconds = parseInt(timer % 60, 10);

            minutes = minutes < 10 ? '0' + minutes : minutes;
            seconds = seconds < 10 ? '0' + seconds : seconds;

            display.text('Отправить код повторно можно будет через ' + minutes + ':' + seconds);

            if (--timer < 0) {
                timer = duration;
                clearInterval(startTimer);
                $('#time-to-send').html('<button type="button" id="repeat-sms_code">Отправить код повторно</button>');
            }

        }, 1000);
    }
    
    // После ввода 5тизначного смс кода формируем кнопку на повторную отправку смс
    $('.input-sms_code').on('input', function(){
        var value = $(this).val();
        if (value.length == 5) {
            display = $('#time-to-send');
            startTimer(timeMinute, display);
        }        
    });
    
    // Отмена операции по смене пароля, номера телефона
    $('#cancel-sms').on('click', function() {
        var valueData = $('#time-to-send').data('value');
        $.ajax({
            type: 'POST',
            url: 'cancel-sms-code',
            data: {
                valueData: valueData,
            },
        }).done(function(response) {
            console.log(response.status);
        });
    });

    /*
     * Генерация нового СМС кода
     */
    $(document).on('click', '#repeat-sms_code', function (){
        display = $('#time-to-send');
        var valueData = $('#time-to-send').data('value');
        startTimer(timeMinute, display);
        $.ajax({
            type: 'POST',
            url: 'generate-new-sms-code',
            data: {
                valueData: valueData
            },
        }).done(function(response) {
//            console.log(response.value);
        });
    });
   
    
    /* Кастомизация элеметнов управления формой, лицевой счет */
    $(".custom-select").each(function() {
        var classes = $(this).attr("class"),
            id = $(this).attr("id"),
            name = $(this).attr("name");
        var template =  '<div class="' + classes + '">';
            template += '<span class="custom-select-trigger">' + $(this).attr("placeholder") + '</span>';
            template += '<div class="custom-options">';
        // Текущий выбранный лицевой счета    
        var currentValue = $('#sources option:selected').val();
        
        $(this).find("option").each(function() {
            var classSelection = ($(this).attr("value") == currentValue) ? 'selection ' : '';            
            template += '<span class="custom-option ' + classSelection + $(this).attr("class") + '" data-value="' + $(this).attr("value") + '">' + $(this).html() + '</span>';
//            $(this).val('selection');
            
        });
        template += '</div></div>';

        $(this).wrap('<div class="custom-select-wrapper"></div>');
        $(this).hide();
        $(this).after(template);
    });

    $(".custom-option:first-of-type").hover(function() {
        $(this).parents(".custom-options").addClass("option-hover");
    }, function() {
        $(this).parents(".custom-options").removeClass("option-hover");
    });

    $(".custom-select-trigger").on("click", function() {
        $('html').one('click',function() {
            $(".custom-select").removeClass("opened");
        });
        $(this).parents(".custom-select").toggleClass("opened");
        event.stopPropagation();
    });


    $(".custom-option").on("click", function() {
        // ID выбранного лицевого счета
        var valueSelect = $(this).data("value");
        // Номер выбранного лицевого счета
        var textSelect = $(this).text();
        // ID текущего лицевого счета
        var currentValue = $('#sources option:selected').val();
        console.log(valueSelect + ' ' + textSelect + ' ' + currentValue);
        $(this).parents(".custom-select-wrapper").find("select").val(valueSelect);
        $(this).parents(".custom-options").find(".custom-option").removeClass("selection");
        $(this).addClass("selection");
        $(this).parents(".custom-select").removeClass("opened");
        $(this).parents(".custom-select").find(".custom-select-trigger").text(textSelect);
        // Смена текущего лицевого счета, ЛК собственник
        switchAccountNumber(currentValue, valueSelect);
    });
    
    /* Кастомизация элеметнов управления формой, категории услуг */
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
        $(this).parents(".custom-select-wrapper-services").find("select").val(valueSelect);
        $(this).parents(".custom-options-services").find(".custom-option-services").removeClass("selection-services");
        $(this).addClass("selection-services");
        $(this).parents(".custom-select-services").removeClass("opened");
        $(this).parents(".custom-select-services").find(".custom-select-trigger-services").text(textSelect);
        filterServicesCategory(valueSelect);
    });

    /*
     * Счетчик подсчета количества введенных символов в textarea
     */
    function limitLettrs(DOMObject, max, labelCount, lostLetter) {
        $(DOMObject).on('keyup keypress blur change', function(){
            // Количество введенных символов
            var count = $(this).val().length;
            
            if (count < max) {
                $(labelCount).text(count);
                $(lostLetter).text('/' + max);
            } else if (count >= max) {
                // Если достигнул лимит введенных символов
                $(labelCount).text(count);
                $(lostLetter).text('/' + max);
                $(this).val($(this).val().substr(0, max));
                return false;                
            }
        });
    }

});
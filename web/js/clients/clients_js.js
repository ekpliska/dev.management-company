/* 
 * For Clients of Modules
 */

$(document).ready(function() {
    
    /*
     * Настройки слайдера
     */
    $('.news-carousel').owlCarousel({
        loop: true,
        margin: 10,
        nav: true,
        items: 3,
        dots: false,
        navText: [
            '<span class="glyphicon glyphicon-arrow-left slider-prev"></span>',
            '<span class="glyphicon glyphicon-arrow-right slider-next"></span>'
        ],
        responsive: {
            0: {
                items: 1
            },
            600: {
                items: 2,
            },
            1000: {
                items: 2
            },
            1440: {
                items: 3
            }
        }
    });

    $('.counters-carousel').owlCarousel({
        loop: true,
        margin: 10,
        nav: true,
        items: 1,
        nav: false,
    });
    
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
    
    /*
     * Переключение текущего лицевого счета,
     */
    $('#switch-current-account').on('change', function() {
        var valueSelect = $('#switch-current-account option:selected').val();
        $.ajax({
            url: '/clients/app-clients/check-account',
            data: {
                newCurrentAccount: valueSelect,
            },
            dataType: 'json',
            type: 'POST',
            success: function() {},
        });
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
            success: function () {},
        });
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
     * Настройки профиля
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
        var url = location.origin + '/receipts/' + accountNumber + '/' + liItem + '.pdf';
        var conteiner = $('.receipts_body');
        $('ul.receipte-of-lists li').removeClass('active');
        $(this).addClass('active');
        
        // Проверяем сущестование pdf, если существует - загружаем фрейм
        $.get(url)
                .done(function (){
                    conteiner.html('<iframe src="' + url + '" style="width: 100%; height: 850px;" frameborder="0">Ваш браузер не поддерживает фреймы</iframe>');
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
            $.post('payments/search-data-on-period?account_number=' + accountNumber + '&date_start=' + startDate + '&date_end=' + endDate + '&type=' + type,
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
        getDataClients(accountNumber, startDate, endDate, '#payments-lists', 'payments');
        
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
            $.post('counters/send-indication?counter=' + uniqueCounter + '&indication=' + curIndication, function(responce) {
                if (responce.success === false) {
                    return false;
                } else if (responce.success === true) {
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
        
        $.ajax({
            url: 'create-paid-request',
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
            error: function (textStatus) {}
        });
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
    $('#date_start-period-counter').on('change', function() {
        var dateValue = $(this).val();
        var nameMonth = dateValue.split('-')[0];
        var year = dateValue.split('-')[1];
        var monthNumber = month[nameMonth.toLowerCase()];
        
        $.post('counters/find-indications?month=' + monthNumber + '&year=' + year, function(response) {
            $('#indication-table').html(response.result);
        });
    });
    
    $(document).on('click', '.print_receipt', function(e) {
        e.preventDefault();
        var urlPDF = $(this).attr('href');
        var w = window.open(urlPDF);
        w.print();
    });
    
    /*
     * Отправка квитанции по почте
     */
    $(document).on('click', '.send_receipt', function(e){
        var dateReceipt = $(this).data('number-receipt');
        var fileURL = $(this).attr('href');
        e.preventDefault();
        $.ajax({
            url: 'send-receipt-to-email',
            method: 'POST',
            data: {
                fileUrl: fileURL,
                dateReceipt: dateReceipt,
            }
        }).done(function(data) {
            var textMess = $('#default_modal-message').find('#default_modal-message__text');
            if (data.success === true) {
                textMess.html(`Квитанция ${dateReceipt} была успешно отправлена на ваш электронный адрес!`);
            } else if (data.success === false) {
                textMess.html('При отправке квитанции возникла ошибка. Обновите страницу и повторите действие еще раз.');
            }
            $('#default_modal-message').modal('show');
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
            url: 'requests/filter-by-type-request',
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
     * Вызов модального окна "Добавление оценки"
     */
    $('a#add-rate').on('click', function(e) {
        var link = $(this).attr('href');
        e.preventDefault();
        $('#add-grade-modal').modal('show');
        $('#add-grade-modal .modal-content .modal-body').load(link);
        e.preventDefault();
        return false;
    });
    
    $(document).on('click', '.btn-set-answer', function() {
        var idBtn = $(this).prop('id');
        var requestID = $(this).data('request');
        var questionsID = $(this).data('question'); 
        var answer = $(this).data('answer'); 
        
        // Родительский контейнер кнопки
        var block = $(this).closest('td');
        $(block).find('.btn-set-grade-active').removeClass('btn-set-grade-active');
        $(this).addClass('btn-set-grade-active');
        
        $.ajax({
            url: 'add-answer-request',
            method: 'POST',
            data: {
                requestID: requestID,
                questionsID: questionsID,
                answer: answer,
            },
        }).done(function(response) {});
        
    });
    
    // 5-тибальная система оценки
    var currentGrade = 5;
    /*
     * Завершение выставления оценки завершенной заявки
     */
    $(document).on('click', '#finished-set-grade', function(){
        var requestID = $(this).data('request');
        var countQuestions = $(this).data('question');
        var countAnswers = $('.btn-set-grade-active').length;
        var countAgreeAnswers = $('.btn-yes.btn-set-grade-active').length;
        var grade = Math.round(currentGrade/(countQuestions/countAgreeAnswers));
        
        if (countQuestions !== countAnswers) {
            $(document).find('#error-message').text('Пожалуйста дайте ответы на все поставленные вопросы');
            return false;
        } else if (countQuestions === countAnswers) {
            $(document).find('#error-message').text('');
            $.ajax({
                url: 'add-score-request',
                method: 'POST',
                data: {
                    grade: grade,
                    requestID: requestID,
                },
            }).done(function(response) {});
        }

    });
    
    /*
     * Закрыть модальное окно "Оцените заявку"
     */
    $(document).on('click', '.grade-modal__close', function(){
        var requestID = $(this).data('request');
        $.post('close-grade-window?request=' + requestID, function(response) {});
    });


    /* End Block of Requests */

    // ************************************************************ //
    // ************   Start Block of Paid Services   ************** //
    // *********************************************************** //
    
    $(document).on('click', '.new-rec', function(e) {
        var link = $(this).attr('href');
        $('#add-paid-request-modal').modal('show');
        $('#add-paid-request-modal .modal-dialog .modal-content .modal-body').load(link);
        e.preventDefault();
        return false;
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

        $.ajax({
            url: 'paid-services/filter-category-services',
            method: 'POST',
            data: {
                categoryId: categoryId,
            },
            success: function(response) {
                $('#services-list').html(response.data);
            },
            error: function() {}
        });

    }
    
    /*
     * Счетчик подстчета количества вводимых символов в поле комментарий к заявке
     */
    limitLettrs('.comment', 1000, '#label-count', '#label-count-left');
    limitLettrs('#commentstorequest-comments_text', 1000, '#label-count', '#label-count-left');


    /*
     * Добавление оценки для закрытой заявки на платную услугу
     */
    $('div[id ^= grade-]').raty({
        score: function() {
            return $(this).attr('data-rating');
        },
        readOnly: function() {
            return $(this).attr('data-rating') == 0 ? false : true;
        },
        click: function(score) {
            let requestID = $(this).attr('data-request');
            
            $.ajax({
                url: 'add-score-request',
                method: 'POST',
                data: {
                    score: score,
                    request_id: requestID,
                },
                success: function(response) {
                    var modalWindow = $('#default_modal-message');
                    var modalMessage = modalWindow.find('#default_modal-message__text');
                    modalWindow.find('.modal-header').html('Оценка качества обслужавания');
                    
                    if (response.success === true) {
                        modalMessage.html('Спасибо, оценка принята! Ваше мнение очень важно для нас');
                    } else if (response.success === false) {
                        modalMessage.html('Ошибка высталения оценки. Обновите страницу и повторите действие еще раз.');
                    }
                    modalWindow.modal('show');
                },
                error: function() {
                    console.log('Error #1000-09');
                }
            });
        }
    });    

    /* End Block of Paid Services */


    // ************************************************************ //
    // ***************   Start Block of Voting    **************** //
    // *********************************************************** //

    /*
     * Отмена участия в голосовании
     */
    $('#renouncement_participate, #renouncement_participate_close').on('click', function(){
        var votingId = $(this).data('voting');
        $.post('renouncement-to-participate?voting_id=' + votingId, function(response) {});
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
    
    /*
     * Отправка голоса участника
     */
    $('.btn-set-voting').on('click', function() {
        var idBtn = $(this).prop('id');
        var questionID = $(this).data('question');
        var typeAnswer = $(this).data('typeAnswer');
        // Родительский контейнер кнопки
        var block = $(this).closest('div');
        $(block).find('.btn-set-voting-active').removeClass('btn-set-voting-active');
        $(this).addClass('btn-set-voting-active');
        
        $.post('send-answer?question_id=' + questionID + '&type_answer=' + typeAnswer, function(response) {
            if (response.success === true) {
                $('.marker-vote-' + questionID).removeClass('show-marker');
                $('#finished-voting-btn').removeClass('btn-hidden');
            }
        });
    });
    
    /*
     * Завершение участия в голосовании
     */
    $('#finished-voting-btn').on('click', function(){
        var votingID = $(this).data('voting');
        var countQuestions = $(this).data('question');
        var countAnswers = $('.btn-set-voting-active').length;
        var modalMessage = $('#participate_modal-message');
        
        if (countQuestions !== countAnswers) {
            modalMessage.find('.vote-message_span').text('Ошибка завершения участия в голосовании');
            modalMessage.find('.modal-title-vote').text('Пожалуйста вернитесь к голосованию и дайте ответы на все заявленные вопросы');
            modalMessage.modal('show');
            return false;
        } else if (countQuestions === countAnswers) {
            $.post('finish-voting?voting_id=' + votingID, function(response) {});
            return true;
        }

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
    
    /*
     * Обработка ввода СМС-кода в форме регистрации на участние в голосовании
     */
    $('.number-sms').on('input', function() {
        var currentInput = $(this).prop('id');
        var numInput = currentInput.toString().slice(-1);
        var nextInput = $('#input-sms-' + ++numInput);
        
        if ($(this).length === 1) {
            $(nextInput).focus();
        }
    });
    
    // Разрешаем вводить только цифры
    $('.number-sms').keypress(function(key) {
        if (key.charCode < 48 || key.charCode > 57 || $(this).val().length > 1) return false;
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
        }).done(function(response) {});
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
        }).done(function(response) {});
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
        $(document).on('keyup keypress blur change', DOMObject, function(){
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
/*
 * Generall JS File
 */

$(document).ready(function() {

    $('.image-link').magnificPopup({type:'image'});
    
    $('.cell-phone').mask('+7 (999) 999-99-99');
    $('.house-phone').mask('+7 (9999) 999-99-99');
    $('.sms-code-input').mask('99999');
    $.mask.definitions['h']='[0-9]';
    $('.account-number').mask('№hhhhhhhhhhh');
    $('.dispatcher-phone').mask('8 999 999-99-99');

    /*
     * Автоматическое выделение текста в текстовых полях поиска
     */
    $('._search-input').on('click', function(){
        $(this).select();
    });

    /*
     * Предварительная загрузка превью одной фотографии
     */
        function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();            
            reader.onload = function (e) {
                $('#photoPreview, #photoPreview-to-edit').attr('src', e.target.result);
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }
    
    $(document).on('change', '#btnLoad, #btnLoad-edit', function(){
        readURL(this);
    });
   
    /*
     * Показывать/Скрывать символы в поле ввода пароля
     */    
        $('input[name=show_password_ch]').on('change', function() {
            var isShow = $(this);
            if (isShow.is(':checked')) {
                $(".show_password").attr("type", "text");
                $("#show_password__text").text("Скрыть пароль");
            } else {
                $(".show_password").attr("type", "password");
                $("#show_password__text").text("Показать пароль");
            }
        });

    /*
     * Работа навигационного меню, Собственник
     */
    $('.menu-toggle').on('click', function(e) {
        $(document.body).css('overflow', 'auto');
        e.preventDefault();
        $(this).toggleClass('menu-toggle_active');
        $('.navbar-menu__items').toggleClass('menu-show');
        $('.navbar-mobile-menu__items').toggleClass('mobile-menu_show');
        $('.menu-toggle_message').text('Меню');
        $('.menu-wrap-manager').toggleClass('menu-show-manager');
        $('.menu_sub-bar').show();
    });
    $(document).on('click', '.menu-toggle_active', function() {
        $(document.body).css('overflow', 'hidden');    
        $('.menu-toggle_message').text('Закрыть');
        $('.menu_sub-bar').hide();
    });

    /*
     * Сабменю, в мобильной версии
     */
    $(document).on('click', '#sub-menu_open', function() {
        $('.mobile-menu__items__sub').toggleClass('mobile-menu__sub-show');
        $('.caret-arrow').toggleClass('up-arrow');
    });

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
                $('#send-request-to-sms, #reset-password-sms').show();
                $('#timer-to-send').html('');
            }

        }, 1000);
    }


    /*
     * Отправка повторного номера телефона
     */
    $('#send-request-to-sms').on('click', function() {
        var labelError = $('#error-message');
        var phoneNumber = $('input[name*="phone"]').val();
        var re = /^\+7\ \([\d]{3}\)\ [\d]{3}-[\d]{2}-[\d]{2}$/;
        var valid = re.test(phoneNumber);
        if (valid === true && phoneNumber.length == 18) {
            $.ajax({
                url: 'signup/send-sms-to-register',
                method: 'POST',
                data: {
                    phoneNumber: phoneNumber,
                },
                success: function (data, textStatus, jqXHR) {
    //                console.log(data);
                },
                error: function (jqXHR, textStatus, errorThrown) {
    //                console.log(textStatus);
                }
            });
            $(this).hide();
            display = $('#timer-to-send');
            startTimer(timeMinute, display);
            return labelError.text('СМС код был выслан на указанный номер телефона');
        }
        return labelError.text('Укажите номер мобильного телефона');

    });

    /*
     * Восстановление пароля
     */
    $('#reset-password-sms').on('click', function() {
        var labelError = $('#error-message');
        var phoneNumber = $('input[name*="phone"]').val();
        var re = /^\+7\ \([\d]{3}\)\ [\d]{3}-[\d]{2}-[\d]{2}$/;
        var valid = re.test(phoneNumber);
        if (valid === true && phoneNumber.length == 18) {
            $.ajax({
                url: 'site/reset-password',
                method: 'POST',
                data: {
                    phoneNumber: phoneNumber,
                },
                success: function (data, textStatus, jqXHR) {},
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(textStatus);                
                }
            });
            $(this).hide();
            display = $('#timer-to-send');
            startTimer(timeMinute, display);
            return labelError.text('СМС код был выслан на указанный номер телефона');
        }
        return labelError.text('Укажите номер мобильного телефона');

    });
    
    /*
     * Удалить все уведомления
     */
    $('.notification_reset').on('click', function() {
        $.post('/notification/remove-notifications', function(response) {});
    });
    
    /*
     * Удалить выбрангое уведомление
     */
    $('.notification_link').on('click', function(e) {
        var notice = $(this).data('notice');
        $.post('/notification/one-notification?notice_id=' + notice, function(response) {});
    });
    
    

    /* Форма */
    $(document).on('focus', '.field-input, .field-input-modal, .field-input-textarea-modal, .field-input-textarea-page', function(){
        $(this).parent().addClass('is-focused has-label');
    });

    $(document).on('blur', '.field-input, .field-input-modal, .field-input-textarea-modal, .field-input-textarea-page', function(){
        $parent = $(this).parent();
        if ($(this).val() == '') {
            $parent.removeClass('has-label');
        }
        $parent.removeClass('is-focused');
    });

    $('.field-input, .field-input-modal, .field-input-textarea-modal, .field-input-textarea-page').each(function(){
        if ($(this).val() !== '') {
            $(this).parent().addClass('has-label');
        }
    });

    /* Скрыть всплывающее сообщение */
    $('.alert-message').on('click', function() {
        $(this).fadeOut(2000);
    });
    
    /* Show password */
    $('.form__show-password').on('mousedown', function(){
         $(".pwd_value").replaceWith($('.pwd_value').clone().attr('type', 'text'));
    })
    .mouseup(function() {
        $(".pwd_value").replaceWith($('.pwd_value').clone().attr('type', 'password'));
    })
    .mouseout(function() {
	$(".pwd_value").replaceWith($('.pwd_value').clone().attr('type', 'password'));
    });

    /*
     * Показать/скрыть часть текста заявки
     */
    $('.text_review').each(function(){
        var textFull = $(this).html();
        var review = textFull;
        var hideFull = '<div class="hide-more"> Скрыть <i class="glyphicon glyphicon-transfer"></i></div>';
        if(review.length > 100) {
            review = review.substring(0, 100) + '...';
            $(this).html(
                    '<div class="read-less">' + review
                    + '<div class="read-more"> Посмотреть весь текст заявки <i class="glyphicon glyphicon-transfer"></i></div></div>' );
        }
        $(this).append('<div class="full-text" style="display: none;">' + textFull + hideFull  + '</div>');
    });
    
    $('.read-more').click(function(){
        $(this).parent().hide('slow', function(){
            $(this).parent().parent().find('.full-text').show();
        });
    });
    
    $(document).on('click', '.hide-more', function(){
        $(this).parent().hide('slow', function(){
            $(this).parent().parent().find('.read-less').show();
        });
    });
    
});    
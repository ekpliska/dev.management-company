/*
 * Generall JS File
 */

$(document).ready(function() {

$('.cell-phone').mask('+7 (999) 999-99-99');
$('.house-phone').mask('+7 (9999) 999-99-99');
$('.sms-code-input').mask('99999');
//$('.indication-input').mask('9.?99');
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
                $('#photoPreview').attr('src', e.target.result);
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }
    
    $("#btnLoad").change(function(){
        readURL(this);
    });
   
/*
 * Предварительная загрузка превью нескольких фотографий
 */

     var maxFileSize = 2 * 1024 * 1024; // (байт) Максимальный размер файла (2мб)
     var queue = {};
     var form = $('form#uploadImages');
     var imagesList = $('#uploadImagesList');
 
     var itemPreviewTemplate = imagesList.find('.item.template').clone();
     itemPreviewTemplate.removeClass('template');
     imagesList.find('.item.template').remove();
 
 
     $('.addImages').on('change', function () {
         var files = this.files;
 
         for (var i = 0; i < files.length; i++) {
             var file = files[i];
 
             if ( !file.type.match(/image\/(jpeg|jpg|png|gif)/) ) {
                 alert( 'Фотография должна быть в формате jpg, png или gif' );
                 continue;
             }
 
             if ( file.size > maxFileSize ) {
                 alert( 'Размер фотографии не должен превышать 2 Мб' );
                 continue;
             }
 
             preview(files[i]);
         }
 
     });
 
     // Создание превью
     function preview(file) {
         var reader = new FileReader();
         reader.addEventListener('load', function(event) {
             var img = document.createElement('img');
 
             var itemPreview = itemPreviewTemplate.clone();
 
             itemPreview.find('.img-wrap img').attr('src', event.target.result);
             itemPreview.data('id', file.name);
 
             imagesList.append(itemPreview);
 
             queue[file.name] = file;
 
         });
         reader.readAsDataURL(file);
     }
 
     // Удаление фотографий
     imagesList.on('click', '.delete-link', function () {
         var item = $(this).closest('.item'),
             id = item.data('id');
 
         delete queue[id];
 
         item.remove();
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
 * Сабменю, в мобтльной версии
 */
$(document).on('click', '#sub-menu_open', function() {
    $('.mobile-menu__items__sub').toggleClass('mobile-menu__sub-show');
    $('.caret-arrow').toggleClass('up-arrow');
});

$('#menu').on('wheel', function(e){
    
    var delta = e.originalEvent.wheelDelta /120;
    
    var _0ID = $('li[id=item-menu-0').html();
    var _1ID = $('li[id=item-menu-1').html();
    var _2ID = $('li[id=item-menu-2').html();
    var _3ID = $('li[id=item-menu-3').html();
    var _4ID = $('li[id=item-menu-4').html();
    
    var first = $('ul.menu-scroll').children().first().html();
    var last = $('ul.menu-scroll').children().last().html();
    
    if (delta > 0) {
        $('li[id=item-menu-4]').html(first);
        $('li[id=item-menu-3]').html(_4ID);
        $('li[id=item-menu-2]').html(_3ID);
        $('li[id=item-menu-1]').html(_2ID);
        $('li[id=item-menu-0]').html(_1ID);
    }
    else {
        $('li[id=item-menu-0]').html(last);        
        $('li[id=item-menu-1]').html(_0ID);        
        $('li[id=item-menu-2]').html(_1ID);        
        $('li[id=item-menu-3]').html(_2ID);        
        $('li[id=item-menu-4]').html(_3ID);        
    }
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
            $('#send-request-to-sms').show();
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
            url: 'send-sms-to-register',
            method: 'POST',
            data: {
                phoneNumber: phoneNumber,
            },
            success: function (data, textStatus, jqXHR) {
                console.log('ok');
                console.log(data.nubmer);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log('ok');                
            }
        });
        $(this).hide();
        display = $('#timer-to-send');
        startTimer(timeMinute, display);
        return labelError.text('СМС код был выслан на указанный номер телефона');
    }
    return labelError.text('Укажите номер мобильного телефона');
    
});


/* Форма */
    $(document).on('focus', '.field-input, .field-input-modal', function(){
        $(this).parent().addClass('is-focused has-label');
    });

    $(document).on('blur', '.field-input, .field-input-modal', function(){
        $parent = $(this).parent();
        if ($(this).val() == '') {
            $parent.removeClass('has-label');
        }
        $parent.removeClass('is-focused');
    });

    $('.field-input, .field-input-modal').each(function(){
        if ($(this).val() !== '') {
            $(this).parent().addClass('has-label');
        }
    });


$(function($) {
    $('.field-input-textarea-modal, .field-input-textarea-page').focus(function(){
        $(this).parent().addClass('is-focused has-label');
    });

    $('.field-input-textarea-modal, .field-input-textarea-page').blur(function(){
        $parent = $(this).parent();
        if($(this).val() == ''){
            $parent.removeClass('has-label');
        }
        $parent.removeClass('is-focused');
    });

    $('.field-input-textarea-modal, .field-input-textarea-page').each(function(){
        if($(this).val() !== ''){
            $(this).parent().addClass('has-label');
        }
    });
});

/* Скрыть всплывающее сообщение */
//$(".alert-message").slideUp(1000).delay(800).fadeIn(700);
$('.alert-message').on('click', function() {
    $(this).fadeOut(2000);
});

/*
 * Скрыть/показать полный текст комментария к заявке
 */
(function( $ ) {
    $.fn.cutstring = function() {
        this.each(function() {
            // Получаем элемент из класса .cutstring
            var me = $(this);
            /*
             * Устанавливаем дата-атрибуты
             * data-display     CSS-свойства display скрываемой части строки
             * data-max-length  Максимальная длина строки. Если строка больше этого значения, лишнее обрезается и скрывается. 
             *                  Если атрибут не указан, используется значение, соответствующее 20% от длинны строки
             * data-show-text   Текст переключателя, когда обрезанная часть строки скрыта >>
             * data-hide-text   Текст переключателя, когда обрезанная часть строки отображается <<
             */
            var settings = {
                display: me.is('[data-display]') ? me.attr('data-display') : 'none',
		maxLength: me.is('[data-max-length]') ? parseInt(me.attr('data-max-length')) : Math.ceil((me.html().length * 20) / 100),
		showText: me.is('[data-show-text]') ? me.attr('data-show-text') : ' &raquo;',
		hideText: me.is('[data-hide-text]') ? me.attr('data-hide-text') : '&laquo; ',
            };
            
            if ( me.html().length > settings.maxLength ) {
                // Обрезаем строку до указанной длины в параметрах, часть строки которую показываем
                var subText1 = me.html().substring(0, settings.maxLength);
                // Остальная часть строки, которую скрываем
                var subText2 = me.html().substring(settings.maxLength);
		var meHellip = $('<span>'+ ( (settings.display == '') ? ' ' : '[...] ' ) +'</span>').addClass('cutstring-hellip');
		var meSuffix = $('<span>'+ subText2 +'</span>').addClass('cutstring-suffix').css('display', settings.display);
		var meToggle = $('<span>'+ ( (settings.display == '') ? settings.hideText : settings.showText ) +'</span>').addClass('cutstring-toggle');
		me.html(subText1).append(meSuffix).append(meHellip).append(meToggle);
		meToggle.click(function() {
                    settings.display = (settings.display == '') ? 'none' : '';
                    meHellip.html( (settings.display == '') ? ' ' : '&hellip; ' );
                    meSuffix.css('display', settings.display);
                    meToggle.html( (settings.display == '') ? settings.hideText : settings.showText );
		});
            }
        })
};

})(jQuery);

$(function() {
    $('.cutstring').cutstring();
});
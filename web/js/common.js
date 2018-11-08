/*
 * Generall JS File
 */

$(document).ready(function() {
  
/*
 * Предварительная загрузка превью аватара
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
 * Работа навигационного меню
 */
$('button.navbar-toggler').click(function() {
    $('body').addClass('modal-open');
    $('.navbar').addClass('hidden');
});

$('.close-menu').click(function() {
    $('body').removeClass('modal-open');
    $('.navbar').removeClass('hidden');
});


/*
 * 
 * @param {type} $
 * @returns {undefined}
 */
$('#send-request-to-sms').on('click', function() {
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
        return alert('Отправить смс код');        
    }
    return alert('Ошибка заполненич номера телефона');
    
});


/* Форма */
$(function($) {
    $('.field-input').focus(function(){
        $(this).parent().addClass('is-focused has-label');
    });

    $('.field-input').blur(function(){
        $parent = $(this).parent();
        if($(this).val() == ''){
            $parent.removeClass('has-label');
        }
        $parent.removeClass('is-focused');
    });

    $('.field-input').each(function(){
        if($(this).val() !== ''){
            $(this).parent().addClass('has-label');
        }
    });
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
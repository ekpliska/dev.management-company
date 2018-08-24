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
    $("input[type=checkbox]").on("change", function() {
        var isShow = $(this);
        if (isShow.is(":checked")) {
            $(".show_password").attr("type", "text");
            $(".show_password__text").text("Скрыть отображение паролей");
        } else {
            $(".show_password").attr("type", "password");
            $(".show_password__text").text("Показать пароли");            
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
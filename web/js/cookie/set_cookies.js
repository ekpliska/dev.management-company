/* 
 * Формирование
 * Получение
 * Запись
 * cookie
 */

$(document).ready(function() {

    /*
     * Запись куки
     * @param {string} name
     * @param {integer} value
     */
    function setCookie(name, value, options) {
        
        options = options || {};
        var expires = options.expires;
        
        if (typeof expires === "number" && expires) {
            var d = new Date();
            d.setTime(d.getTime() + expires * 1000);
            expires = options.expires = d;
        }
        value = encodeURIComponent(value);

        var updatedCookie = name + "=" + value;

        for (var propName in options) {
            updatedCookie += "; " + propName;
            var propValue = options[propName];
            if (propValue !== true) {
                updatedCookie += "=" + propValue;
            }
        }

        document.cookie = updatedCookie;

    }
    
    /*
     * Администратор, Жилой комплекс
     * Записываем в куку ID выбранного дома
     * При перезагрузке страницы информация о выбранном доме его характеристики,
     * вложения и списко квартир отображаются
     */
    $(document).on('click', '#house_link', function(){
        var house = $(this).attr('href');
        house = house.replace(/[^0-9]/gim, '');
        setCookie('choosingHouse', house);
        alert(document.cookie);
    });
    
});
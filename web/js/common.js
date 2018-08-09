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

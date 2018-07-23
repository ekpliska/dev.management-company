$(document).ready(function() {
//    $('.add__rent').hide();
//    
//    
//    $("#addRent").change(function(){
//        if ($(this).attr("checked")) {
//            $('.add__rent').fadeIn().show();
//        } else {
//            $('.add__rent').fadeOut(300); 
//        }
//    });
        
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
     * Раздел "Лицевой счет"
     * Форма - Добавить лицевой счет
     */
    // Блокируем список доступных арендаторов, и кнопку добавить арендатора
    $('#list_rent').prop('disabled', true);
    $('.btn__add-rent').prop('disabled', true);
    
    // В зависимости от checkBox "Арендатор" скрываем/показываем элементы
    $('#isRent').change(function() {
        if (this.checked) {
            $('#list_rent').prop('disabled', false);
            $('.btn__add-rent').prop('disabled', false);
        } else {
            $('#list_rent').prop('disabled', true);
            $('.btn__add-rent').prop('disabled', true);            
        }
    });
    
    
});

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
    
});

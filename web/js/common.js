$(document).ready(function() {
    
    $('.panel__add_rent').hide();
    
    $('#addRent').change(function() {
        $('.panel__add_rent').show();
    });
    
    
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

/* 
 * For Managers of Modules
 */

$(document).ready(function() {
    
    // ******************************************************** //
    // ************    Start Block of Profile    ************** //
    // ******************************************************** //
    
    /*
     * Формирование зависимых списков выбора Подразделения и Должности Администратора
     */
    $('.department_list').on('change', function(e) {
        $.post('show-post?departmentId=' + $(this).val(),
        function(data) {
            $('.posts_list').html(data);
        });
    });
    
    /*
     * Блокировать/Разблокировать Учетную запись Собственника на портале
     */
    $('body').on('click', '.block_user', function(e) {
        e.preventDefault();
        var userId = $(this).data('user');
        var statusClient = $(this).data('status');        
        $.ajax({
            url: 'block-client-in-view',
            method: 'POST',
            data: {
                userId: userId,
                statusClient: statusClient,
            },
            success: function(response) {                
                if (response.status == 2) {
                    $('.block_user').text('Разблокировать');
                    $('.block_user').removeClass('btn-danger');
                    $('.block_user').addClass('btn-success');
                    $('.block_user').data('status', 1);
                } else {
                    if (response.status == 1) {
                        $('.block_user').text('Заблокировать');
                        $('.block_user').addClass('btn-danger');
                        $('.block_user').removeClass('btn-success');
                        $('.block_user').data('status', 2);
                    }
                }
            },
            error: function() {
                console.log('Error #2000-01');
            },
        });
        return false;
    });
    

});
    



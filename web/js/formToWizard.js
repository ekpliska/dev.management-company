

function validateStepOne(account, summ, square) {
    if ( !account || account.length < 11 
            || !summ || summ < 0 
            || !square || square < 0 ) {
        $('.step-one-message').text('Вы используете некорректные данные');
        return false;
    }
    $('.step-one-message').text('');
    $.ajax({
        url: 'validate-step-one',
        data: {
            account: account,
            summ: summ,
            square: square,
        },
        success: function (response, textStatus, jqXHR) {
            if (response.success === false) {
                $('.step-one-message').text('Вы используете некорректные данные 1');
                return false;
            } else if (response.success === true) {
                $('.step-one-message').text('Все ок');
            }
        },
    });
    return true;
}


$('#step0Next').on('click', function(e){
    e.preventDefault();
    var accountNumber = $('.account-number-input').val();
    var lastSum = $('.last-summ-input').val();
    var square = $('.square-input').val();

    if (validateStepOne(accountNumber, lastSum, square)) {
        alert(accountNumber);
    }

});
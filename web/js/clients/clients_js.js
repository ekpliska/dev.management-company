/* 
 * For Clients of Modules
 */

$(document).ready(function() {

/*--------------------------------
 *  Start Block of Profile 
 --------------------------------*/

/* Обработка события при клике на checkBox "Арендатор"
 * Если за лицевым счетом закреплен арендатор, то 
 * выводим модальное окно для управления учетной записью арендатора
 */
    $("#is_rent").change(function(e) {
        var rentsId = $("input[id=_rents]").val();
        if ($("input").is("#_rents")) {
            $("#changes_rent").modal("show");
            $.ajax({
                url: "get-rent-info?rent=" + rentsId,
                method: "POST",
                dataType: "json",
                data: {
                    rent_id: rentsId,
                },
                success: function(response) {
                    if (response.status) {
                        $("#changes_rent #rent-surname").text(response.rent.rents_surname);
                        $("#changes_rent #rent-name").text(response.rent.rents_name);
                        $("#changes_rent #rent-second-name").text(response.rent.rents_second_name);
                    } else {
                        console.log("Ошибка при получении данных арендатора");
                    }
                }
            });
        } else {
            $("#add-rent-modal").modal("show");
        }
    });


/* 
 * Подтверждение удаления арендатора 
 */
    $("#confirm-delete").on("click", ".btn-ok-delete", function(e) {
        var id = $(this).data("recordId");
        $.ajax({url: "change-rent-profile?action=delete&rent=" + id})
    });
    
    $("#confirm-delete").on("show.bs.modal", function(e) {
        var data = $(e.relatedTarget).data();
        $(".title", this).text(data.recordFullname);
        $(".btn-ok-delete", this).data("recordId", data.recordId);
    });


/*
 * Объединение арендатора с лицевым счетом
 */
    $("#bind-rent-modal").on("click", ".btn-ok-bind", function(e) {
        var rentId = $(this).data("rent");
        var accountId = $("#_list-account-rent :selected").text();
        console.log(rentId + " " + accountId);
        $.ajax({
            url: "change-rent-profile?action=bind&rent=" + rentId + "&account=" + accountId,
            method: "GET",
            success: function(response) {
                console.log("Объединение арендатора с лицвым счетом OK");
            },
            error: function() {
                console.log("Объединение арендатора с лицвым счетом error");
            }
        })
    });
    /*
     * Перед загрузкой модального окна подтвержадения удаления арендатора
     * получаем data параметры
     */ 
    $("#bind-rent-modal").on("show.bs.modal", function(e) {
        var data = $(e.relatedTarget).data();
        $(".fullname", this).text(data.rentFullname);
        $(".btn-ok-bind", this).data("rent", data.rent);
    });


/*
 * Обработка событий в модальном окне "Дальнейшие действия с учетной записью арендатора"
 */
    // Закрыть модальное окно
    $(".changes_rent__close").on("click", function() {
        $("#is_rent").prop("checked", true);
    });
    
    // Удалить данные арендатора из системы
    $(".changes_rent__del").on("click", function() {
    
        var rentsId = $("input[id=_rents]").val();
        var accountId = $("#_list-account :selected").text();
        $.get({
            url: "change-rent-profile?action=delete&rent=" + rentsId + "&account=" + accountId,
            method: "GET",
            success: function(response) {
                console.log("Удаление арендатора OK");
            },
            error: function() {
                console.log("Удаление арендатора Error");
            }
        });
    });
    
    // Отвязать арендатора от лицевого счета
    $(".changes_rent__undo").on("click", function() {
    
        var rentsId = $("input[id=_rents]").val();
        var accountId = $("#_list-account :selected").text();
        $.get({
            url: "change-rent-profile?action=undo&rent=" + rentsId + "&account=" + accountId,
            method: "GET",
            success: function(response) {
                console.log("Отвязать арендатора OK");
            },
            error: function() {
                console.log("Отвязать арендатора Error");
            }
        });
    });


/*
 * Сабмит основной формы профиля
 * Перед отправкой проверяем валидацию формы "Аренадатор"
 */
    $("body").on("beforeSubmit", "form#profile-form", function (e) {
        e.preventDefault();
        // Получаем данные из формы Арендатор
        var rentForm = $("#edit-rent").serialize();
        // Получаем количество ошибок на форме Арендатор
        var countError = $("#edit-rent").find(".has-error").length;
        
        // Если имеются ошибки и форма Арендатора существует, отправку основной формы профиля останавливаем
        if (countError > 0 && rentForm) {
            return false;
        } else if (countError === 0 && rentForm) {
            $.ajax({
                url: "save-rent-info",
                data: rentForm,
                method: "POST",
                typeData: "json",
                success: function(data) {
                    if (data.status == false) {
                        $(".error-message").text("Заполните поля корректными данными");
                    }
                },
                error: function(data) {
                    console.log("error for save rent info");
                }
            });
        }
    });

/*
 * Форма "Добавить нового Арендатора"
 */
    /*
     * При загрузке модального окна, получаем
     * ID выбранного лицевого счета
     */
    $("#add-rent-modal").on("show.bs.modal", function(e) {
        var accountId = $("#_list-account :selected").text();
        $("#_personal-account").val(accountId);
        $(".btn__add_rent", this).data("accountId", accountId);
    });
    
    // Очистить поля ввода, клик по кнопкам "Отмена", "x"
    $("#add-rent-modal .add-rent-modal__close").on("click", function() {
        $("#add-rent input").val("");
        $("#add-rent-modal .modal-body").removeClass("has-error");
        $("#add-rent-modal .modal-body").removeClass("has-success");
        $("#add-rent-modal .form-group").removeClass("has-success"); 
        $("#add-rent-modal .form-group").removeClass("has-error");
        $("#add-rent-modal").find(".help-block").text("");
     });
    
    // Сохраняем нового Арендатора
    $("#add-rent").on("beforeSubmit", function() {
        var addRentForm = $(this);
        $.ajax({
            url: "add-new-rent",
            type: "POST",
            data: addRentForm.serializeArray(),
            succeess: function(response) {
                if (response.status === false) {
                    console.log("Error when data try to saved (add rent form)");
                }
            },
            error: function() {
                console.log("Error #1 when data try to saved (add rent form)");
            },
        });
    });
   

/* End Block of Profile*/

/*--------------------------------
 *  Start Block of Personal Account 
 --------------------------------*/
/*
 * Смена информации о лицевом счете,
 * dropDownList _list-account-all
 */    
    $("._list-account-all").on("change", function() {
        var accountId = $(this).val();
        $.ajax({
            url: "list?account=" + accountId,
            method: "GET",
            type: "json",
            success: function(response) {
                $("#account-info").html(response.data);
            },
            error: function() {
                console.log("Error");
            },
        });
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
    
    /*
     * Если при создании лицевого счета 
     * арендатор выбран из списка, блокируем кнопку "Добавить арендатора"
     */
    $('#list_rent').change(function() {
        if (!$(this).val()) {
            $('.btn__add-rent').prop('disabled', false);
        } else {
            $('.btn__add-rent').prop('disabled', true);            
        }
    });



/*
 * Раздел "Приборы учета"
 */
    // $(".btn__add_indication").prop("disabled", true);
    
    /*
     * Поля ввода для показания приборов учета блокируем
     */ 
    var ind = $(".indication_val");
    ind.prop("disabled", true);

    /*
     * Если нажата кнопка "Ввести показания"
     * текстовые поля для ввода показаний делаем доступными
     */
    $(".btn__add_indication").on("click", function() {
        ind.prop("disabled", false);
    });

/* End Block of Personal Account */



/*--------------------------------
 *  Start Block of Requests
 --------------------------------*/
    
    // Очистить форму заполнения заявки, если пользователь нажал в модальном окне "Отмена"
    $(".request__btn_close").on("click", function () {
        $('#add-request')[0].reset();
    });
    
/* End Block of Requests */


})


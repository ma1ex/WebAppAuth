/* Registration */
$('#registration').submit(function(e) {
    e.preventDefault();

    var data = new FormData(this);

    $.ajax({
        type:'POST',
        url: 'http://auth.local/auth/add/',
        data: data,
        cache: false,
        contentType: false,
        processData: false,
        success: function(response){
            swal({
                title: "Отлично!",
                text: "Пользователь успешно зарегистрирован!",
                icon: "success",
            }).then(() => {
                location.reload();
            });
        },
        error: function(response, status, error) {
            var errors = response.responseJSON;

            if (errors.errors) {
                errors.errors.forEach(function(data, index) {
                    var field = Object.getOwnPropertyNames (data);
                    var value = data[field];
                    var div = $("#"+field[0]).closest('div');
                    div.addClass('has-danger');
                    div.children('.form-control-register').text(value);
                });
            }
        }
    });
});

/* Registration */
$('#signin').submit(function(e) {
    e.preventDefault();

    var data = new FormData(this);

    $.ajax({
        type:'POST',
        url: 'http://auth.local/auth/signin/',
        data: data,
        cache: false,
        contentType: false,
        processData: false,
        success: function(response) {
            swal({
                title: "Отлично!",
                text: "Добро пожаловать на сайт!",
                icon: "success",
            }).then(() => {
                location.href = 'http://auth.local/';
            });
        },
        error: function(response, status, error){
            var errors = response.responseJSON;

            if (errors.errors === 'forbidden') {
                swal({
                    title: "Ошибка!",
                    text: "Неверные логин или пароль!",
                    icon: "error",
                }).then(() => {
                    location.reload();
                });
            }

            if (errors.errors) {
                errors.errors.forEach(function(data, index) {
                    var field = Object.getOwnPropertyNames (data);
                    var value = data[field];
                    var div = $("#"+field[0]).closest('div');
                    div.addClass('has-danger');
                    div.children('.form-control-signin').text(value);
                });
            }
        }
    });
});
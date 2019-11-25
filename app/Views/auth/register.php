<div class="container">

    <div class="row">
        <div class="col-7">
            <h1><?php echo $page_caption ?></h1>
        </div>
    </div>

    <noscript>
        <div class="alert alert-danger" role="alert">
            <h4 class="alert-heading">Что-то пошло не так...</h4>
            <p>В Вашем браузере отключено выполнение скриптов! Нет скриптов, нет регистрации.</p>
            <hr>
            <p class="mb-0">Устраните ошибку и попробуйте снова.</p>
        </div>
    </noscript>

    <div class="row justify-content-center">
        <div class="col-8">
            <form id="registration" method="post">
                <div class="form-group">
                    <label for="login"><sup class="text-red">*</sup> Login:</label>
                    <input type="text" class="form-control" id="login" name="login" aria-describedby="emailHelp" placeholder="Введите login" required>
                    <div class="form-control-register"></div>
                </div>
                <div class="form-group">
                    <label for="password"><sup class="text-red">*</sup> Пароль:</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Пароль" required>
                    <div class="form-control-register"></div>
                </div>
                <div class="form-group">
                    <label for="repeat-password"><sup class="text-red">*</sup> Подтверждение пароля:</label>
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Повторите пароль" required>
                    <div class="form-control-register"></div>
                </div>
                <div class="form-group">
                    <label for="email"><sup class="text-red">*</sup> Email:</label>
                    <input type="email" class="form-control" id="email" name="email" aria-describedby="emailHelp" placeholder="Введите email" required>
                    <div class="form-control-register"></div>
                </div>
                <div class="form-group">
                    <label for="name"><sup class="text-red">*</sup> Имя:</label>
                    <input type="text" class="form-control" id="name" name="name" aria-describedby="emailHelp" placeholder="Введите имя" required>
                    <div class="form-control-register"></div>
                </div>
                <button type="submit" class="btn btn-primary">Зарегистрироваться</button>
            </form>
        </div>
    </div>

</div>
<div class="container">

    <div class="row">
        <div class="col-7">
            <h1><?php echo $page_caption ?></h1>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-8">
            <form id="signin" method="post">
                <div class="form-group">
                    <label for="login">Логин</label>
                    <input type="text" class="form-control" id="login" name="login" aria-describedby="emailHelp" placeholder="Введите логин">
                    <div class="form-control-signin"></div>
                </div>
                <div class="form-group">
                    <label for="password">Пароль</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Пароль">
                    <div class="form-control-signin"></div>
                </div>
                <button type="submit" class="btn btn-primary">Войти</button>
            </form>
        </div>
    </div>

</div>
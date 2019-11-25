<div class="container">

    <div class="row">
        <div class="col-7">
            <h1><?php echo $page_caption ?></h1>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-8">
            <form id="registration">
                <div class="form-group">
                    <label for="name">Имя</label>
                    <input type="text" class="form-control" id="name" name="name" aria-describedby="emailHelp" placeholder="Введите имя">
                    <div class="form-control-feedback"></div>
                </div>
                <div class="form-group">
                    <label for="password">Пароль</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Пароль">
                    <div class="form-control-feedback"></div>
                </div>
                <button type="submit" class="btn btn-primary">Войти</button>
            </form>
        </div>
    </div>

</div>
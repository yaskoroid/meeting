<div class="container">
    <form action="" name="form" method="post" class="form-horizontal">
        <div class="form-group col-sm-12">
            <h2 class="form-signin-heading">Авторизация</h2>
        </div>
        <div class="form-group">
            <label for="inputEmail" class="control-label col-sm-3">Пользователь</label>

            <div class="col-sm-9">
                <input type="text" name="login2" class="form-control" placeholder="Логин" required autofocus>
            </div>
        </div>
        <div class="form-group">
            <label for="inputPassword" class="control-label col-sm-3">Пароль</label>

            <div class="col-sm-9">
                <input type="password" name="password" class="form-control" placeholder="Пароль" required>
            </div>
        </div>
        <?php if (isset($response)) { ?>
            <?php if (empty($error)) { ?>
                <div class="form-group">
                    <div class="col-sm-12">
                        <label class="text-success bg-success center-block">
            <?php } else { ?>
                <div class="form-group">
                    <div class="col-sm-12">
                        <label class="text-danger bg-danger center-block">
            <?php } ?>
            <?php print $response ?>
                        </label>
                     </div>
                 </div>
        <?php } ?>
        <div class="form-group">
            <div class="col-sm-12">
            <button name="login" class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
            </div>
        </div>

    </form>
</div>
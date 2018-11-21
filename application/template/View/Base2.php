<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <!-- Defaults -->
    <script type="text/javascript">
        <?php if (!empty($def)) print $def->get();?>
    </script>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css"
          integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">
    <!-- Bootstrap needs jQuery, so download it -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
            integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous">
    </script>
    <script src="http://code.jquery.com/jquery-latest.min.js"></script>
    <!-- Latest compiled and minified JavaScript -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"
            integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T" crossorigin="anonymous">
    </script>
    <script src="/js/bootstrap/notify/bootstrap-notify.min.js"></script>
    <!-- Font Awesome -->
    <link rel='stylesheet' href='https://use.fontawesome.com/releases/v5.4.1/css/all.css' integrity='sha384-5sAR7xN1Nv6T6+dT2mhtzEpVJvfS3NScPQTrOxhwjIuvcA67KV2R5Jz6kr4abQsz' crossorigin='anonymous'>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="shortcut icon" type="image/x-icon" href="/favicon.ico">
    <script type="text/javascript" src="/js/script.js"></script>
    <title><?php print $title ?></title>
    <meta name="description" content="<?php print $description ?>">
    <meta name="keywords" content="<?php print $keywords ?>">
</head>
<body>
<!-- Меню -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="/users">Собрание</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar" aria-controls="navbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbar" aria-expanded="false" style="height: 1px;" role="menu">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item<?php if ($page == "Home") print (' active') ?>"><a class="nav-link" href="/home">Дом</a></li>
                <li class="nav-item<?php if ($page == "User") print (' active') ?>"><a class="nav-link" href="/user">Пользователи</a></li>
                <li class="nav-item<?php if ($page == "About") print (' active') ?>"><a class="nav-link" href="/about">О приложении</a></li>
                <li class="nav-item<?php if ($page == "Login") print (' active') ?>"><a class="nav-link" href="/login">Войти</a></li>
            </ul>
            <ul class="navbar-nav navbar-right">
                <?php if (!empty($this->_contextService->getUser())) { ?>
                <li class="nav-item"><p><?php print $this->_contextService->getUser()->login ?></p></li>
                <li class="nav-item"><a class="nav-link" href="/logout">Выйти</a></li>
                <?php } else { ?>
                <li class="nav-item" id="label">Авторизируйтесь</li>
                <?php } ?>
            </ul>
        </div>
    </div>
</nav>
<!-- Подключаем конкретную страницу (экшн) -->
<?php include "application/view/" . $contentView; ?>
</body>
</html>
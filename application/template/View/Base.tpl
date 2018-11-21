<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    {% if def is not empty %}
    <!-- Defaults -->
    <script type="text/javascript">
        {% for defItemKey in def|keys %}
        {{defItemKey}} = "{{ def[defItemKey] }}";
        {% endfor %}
    </script>
    {% endif %}
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
    <title>{{ title }}</title>
    <meta name="description" content="{{ description }}">
    <meta name="keywords" content="{{ keywords }}">
</head>
<body>
<!-- Меню -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="/">Собрание</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar" aria-controls="navbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbar" aria-expanded="false" style="height: 1px;" role="menu">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item{{ page == 'Home' ? ' active' : '' }}"><a class="nav-link" href="/home">Дом</a></li>
                {% if user %}
                    <li class="nav-item{{ page == 'User' ? ' active' : '' }}"><a class="nav-link" href="/user">Пользователи</a></li>
                    {% if userType and userType.role != 'customer' %}
                        <li class="nav-item{{ page == 'Task' ? ' active' : '' }}"><a class="nav-link" href="/task">Задачи</a></li>
                        <li class="nav-item{{ page == 'Settings' ? ' active' : '' }}"><a class="nav-link" href="/settings">Настройки</a></li>
                    {% endif %}
                {% endif %}
                <li class="nav-item{{ page == 'About' ? ' active' : '' }}"><a class="nav-link" href="/about">О приложении</a></li>
                {% if not user %}
                <li class="nav-item{{ page == 'Login' ? ' active' : '' }}"><a class="nav-link" href="/login">Войти</a></li>
                {% endif %}
            </ul>
            <ul class="navbar-nav navbar-right">
                {% if user %}
                    <li class="nav-item"><p>{{ user.login }}</p></li>
                    <li class="nav-item"><a class="nav-link" href="/logout">Выйти</a></li>
                {% else %}
                    <li class="nav-item" id="label">Авторизируйтесь</li>
                {% endif %}
            </ul>
        </div>
    </div>
</nav>
<!-- Подключаем конкретную страницу (экшн) -->
{% block pageContent %}
<div>No page content</div>
{% endblock %}
</body>
</html>
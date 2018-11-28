{% extends "base.tpl" %}
{% block pageContent %}
<script type="text/javascript" src="/js/controller/login.js"></script>
<div class="container">
    <form action="" name="loginForm" method="post" class="form-horizontal">
        <div class="form-group col-sm-12">
            <h2 class="form-signin-heading">Авторизация</h2>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-3">Пользователь</label>
            <div class="col-sm-9">
                <input type="text" name="login" class="form-control" placeholder="Логин" required autofocus>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-3">Пароль</label>
            <div class="col-sm-9">
                <input type="password" name="password" class="form-control" placeholder="Пароль" required>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-6">Забыли пароль?</label>
            <div class="col-sm-12">
                <a class="js-forgot-password" href="">Отправить письмо для изменения пароля на Вашу почту</a>
            </div>
        </div>
        {% if response %}
            <div class="form-group">
                <div class="col-sm-12">
                    {% if error == null %}
                        <label class="text-light bg-success center-block">{{ response }}</label>
                    {% else %}
                        <label class="text-light bg-danger center-block">{{ response }}</label>
                    {% endif %}
                 </div>
             </div>
        {% endif %}
        <div class="form-group">
            <div class="col-sm-12">
            <button class="btn btn-lg btn-primary btn-block" type="submit">Войти</button>
            </div>
        </div>
    </form>
</div>
{% endblock %}
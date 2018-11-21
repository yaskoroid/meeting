{% extends "base.tpl" %}
{% block pageContent %}
{% if user == null%}
    <div class="container">
        <div>Приложение-задачник для быстрого доступа и контроля заданий пользователей</div>
        <div class="form-group">
            <div class="col-sm-5">
                <a class="btn btn-lg btn-primary btn-block" href="/about">О приложении</a>
            </div>
        </div></div>
{% else %}
    <div class ="container">
        <div class="form-horizontal">
            <div>Приложение-задачник для быстрого доступа и контроля заданий пользователей</div>
            <div class="form-group">
                <div class="col-sm-12">
                    <button class="btn btn-lg btn-primary btn-block">Посмотреть все задачи</button>
                </div>
            </div>
        </div>
    </div>
{% endif %}
{% endblock %}
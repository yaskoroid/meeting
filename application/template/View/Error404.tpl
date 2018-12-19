{% extends "base.tpl" %}
{% block pageContent %}
<div class="container">
    <h1>
        Ошибка 404
    </h1>

    <h2>
        Страница на найдена!
    </h2>

    <div class="col-md-12">
        <img class="center-block img-responsive" src="{{image404FilePath}}">
    </div>
</div>
{% endblock %}
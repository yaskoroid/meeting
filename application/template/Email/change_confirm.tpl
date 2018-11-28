{% extends "base.tpl" %}
{% block emailContent %}
<h1>{{ title }}</h1>
<form method="{{ method }}" action="{{ action }}">
    <p>{{ text }}</p>
    <a href="{{action}}">Продолжить на сайте</a>
</form>
{% endblock %}
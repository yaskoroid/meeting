{% extends "base.tpl" %}
{% block emailContent %}
<h1>{{ title }}</h1>
<form method="{{ method }}" action="{{ action }}">
    <p>{{ text }}</p>
    <a href="{{action}}">Подтерждение</a>
    <button type="submit" class="btn btn-success">Подтерждение</button><br>
</form>
{% endblock %}
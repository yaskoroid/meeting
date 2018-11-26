{% extends "base.tpl" %}
{% block pageContent %}
<div class="container">
    <form action="{{action}}" name="confirmForm" method="{{method}}" class="form-horizontal">
        <div class="form-group col-sm-12">
            <h2 class="form-signin-heading">{{header}}</h2>
        </div>
        {% if inputs is not empty %}
            {%for input in inputs%}
            <div class="form-group">
                <label class="control-label col-sm-3">{{input.text}}</label>
                <div class="col-sm-9">
                    <input type="text" name="{{input.name}}" class="form-control" placeholder="{{input.placeholder}}" required{{ input.autofocus ? ' autofocus' : '' }}">
                </div>
            </div>
            {%endfor%}
        {% endif %}
        {% if response %}
        <div class="form-group">
            <div class="col-sm-12">
                {% if response.error == null %}
                <label class="text-light bg-success center-block">{{ response.responseText }}</label>
                {% else %}
                <label class="text-light bg-danger center-block">{{ response.responseText }}</label>
                {% endif %}
            </div>
        </div>
        {% endif %}
        {% if submit %}
        <div class="form-group">
            <div class="col-sm-12">
                <button class="btn btn-lg btn-primary btn-block" type="submit" name"{{submit.submitName}}">{{submit.submitText}}</button>
            </div>
        </div>
        {% endif %}

    </form>
</div>
{% endblock %}
{% extends "base.tpl" %}
{% block pageContent %}
<script type="text/javascript" src="/js/controller/confirm.js"></script>
{% if intent is not empty %}
<!-- Intent value -->
<script type="text/javascript">
    ACTION_INTENT = "{{ intent }}";
</script>
{% endif %}
<div class="container">
    <form action="" name="confirmForm" method="post" class="form-horizontal">
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
        {% if cancel %}
        <div class="form-group">
            <div class="col-sm-12">
                <button class="btn btn-lg btn-danger btn-block" type="cancel" name="{{cancel.name}}">{{cancel.text}}</button>
            </div>
        </div>
        {% endif %}
        {% if submit %}
        <div class="form-group">
            <div class="col-sm-12">
                <button class="btn btn-lg btn-primary btn-block" type="submit" name="{{submit.name}}">{{submit.text}}</button>
            </div>
        </div>
        {% endif %}

    </form>
</div>
{% endblock %}
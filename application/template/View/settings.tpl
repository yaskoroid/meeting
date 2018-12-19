{% extends "base.tpl" %}
{% block pageContent %}
<script type="text/javascript" src="/js/controller/settings.js"></script>
<script data-require="MomentJS@2.10.0" data-semver="2.10.0" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.1/js/tempusdominus-bootstrap-4.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.1/css/tempusdominus-bootstrap-4.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/locale/ru.js"></script>

<div class="container">
    <form action="" name="settingsForm" method="post" class="form-horizontal">
        <div class="form-group col-sm-12">
            <h2 class="form-signin-heading">Настройки</h2>
            <div class="js-content"></div>
        </div>
    </form>
</div>
{% endblock %}
<?php

/* login.tpl */
class __TwigTemplate_9ca765933e330dacb017c83abfc074f01995c4578fb283f61beb188faef863f2 extends Twig_Template
{
    private $source;

    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        // line 1
        $this->parent = $this->loadTemplate("base.tpl", "login.tpl", 1);
        $this->blocks = array(
            'pageContent' => array($this, 'block_pageContent'),
        );
    }

    protected function doGetParent(array $context)
    {
        return "base.tpl";
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 2
    public function block_pageContent($context, array $blocks = array())
    {
        // line 3
        echo "<script type=\"text/javascript\" src=\"/js/controller/login.js\"></script>
<div class=\"container\">
    <form action=\"\" name=\"loginForm\" method=\"post\" class=\"form-horizontal\">
        <div class=\"form-group col-sm-12\">
            <h2 class=\"form-signin-heading\">Авторизация</h2>
        </div>
        <div class=\"form-group\">
            <label class=\"control-label col-sm-3\">Пользователь</label>
            <div class=\"col-sm-9\">
                <input type=\"text\" name=\"login\" class=\"form-control\" placeholder=\"Логин\" required autofocus>
            </div>
        </div>
        <div class=\"form-group\">
            <label class=\"control-label col-sm-3\">Пароль</label>
            <div class=\"col-sm-9\">
                <input type=\"password\" name=\"password\" class=\"form-control\" placeholder=\"Пароль\" required>
            </div>
        </div>
        <div class=\"form-group\">
            <label class=\"control-label col-sm-6\">Забыли пароль?</label>
            <div class=\"col-sm-12\">
                <a class=\"js-forgot-password\" href=\"\">Отправить письмо для изменения пароля на Вашу почту</a>
            </div>
        </div>
        ";
        // line 27
        if (($context["response"] ?? null)) {
            // line 28
            echo "            <div class=\"form-group\">
                <div class=\"col-sm-12\">
                    ";
            // line 30
            if ((($context["error"] ?? null) == null)) {
                // line 31
                echo "                        <label class=\"text-light bg-success center-block\">";
                echo twig_escape_filter($this->env, ($context["response"] ?? null), "html", null, true);
                echo "</label>
                    ";
            } else {
                // line 33
                echo "                        <label class=\"text-light bg-danger center-block\">";
                echo twig_escape_filter($this->env, ($context["response"] ?? null), "html", null, true);
                echo "</label>
                    ";
            }
            // line 35
            echo "                 </div>
             </div>
        ";
        }
        // line 38
        echo "        <div class=\"form-group\">
            <div class=\"col-sm-12\">
            <button class=\"btn btn-lg btn-primary btn-block\" type=\"submit\">Войти</button>
            </div>
        </div>
    </form>
</div>
";
    }

    public function getTemplateName()
    {
        return "login.tpl";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  86 => 38,  81 => 35,  75 => 33,  69 => 31,  67 => 30,  63 => 28,  61 => 27,  35 => 3,  32 => 2,  15 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("{% extends \"base.tpl\" %}
{% block pageContent %}
<script type=\"text/javascript\" src=\"/js/controller/login.js\"></script>
<div class=\"container\">
    <form action=\"\" name=\"loginForm\" method=\"post\" class=\"form-horizontal\">
        <div class=\"form-group col-sm-12\">
            <h2 class=\"form-signin-heading\">Авторизация</h2>
        </div>
        <div class=\"form-group\">
            <label class=\"control-label col-sm-3\">Пользователь</label>
            <div class=\"col-sm-9\">
                <input type=\"text\" name=\"login\" class=\"form-control\" placeholder=\"Логин\" required autofocus>
            </div>
        </div>
        <div class=\"form-group\">
            <label class=\"control-label col-sm-3\">Пароль</label>
            <div class=\"col-sm-9\">
                <input type=\"password\" name=\"password\" class=\"form-control\" placeholder=\"Пароль\" required>
            </div>
        </div>
        <div class=\"form-group\">
            <label class=\"control-label col-sm-6\">Забыли пароль?</label>
            <div class=\"col-sm-12\">
                <a class=\"js-forgot-password\" href=\"\">Отправить письмо для изменения пароля на Вашу почту</a>
            </div>
        </div>
        {% if response %}
            <div class=\"form-group\">
                <div class=\"col-sm-12\">
                    {% if error == null %}
                        <label class=\"text-light bg-success center-block\">{{ response }}</label>
                    {% else %}
                        <label class=\"text-light bg-danger center-block\">{{ response }}</label>
                    {% endif %}
                 </div>
             </div>
        {% endif %}
        <div class=\"form-group\">
            <div class=\"col-sm-12\">
            <button class=\"btn btn-lg btn-primary btn-block\" type=\"submit\">Войти</button>
            </div>
        </div>
    </form>
</div>
{% endblock %}", "login.tpl", "C:\\Program Files (x86)\\Ampps\\www\\roman.com\\application\\template\\View\\login.tpl");
    }
}

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
        ";
        // line 21
        if (($context["response"] ?? null)) {
            // line 22
            echo "            <div class=\"form-group\">
                <div class=\"col-sm-12\">
                    ";
            // line 24
            if ((($context["error"] ?? null) == null)) {
                // line 25
                echo "                        <label class=\"text-light bg-success center-block\">";
                echo twig_escape_filter($this->env, ($context["response"] ?? null), "html", null, true);
                echo "</label>
                    ";
            } else {
                // line 27
                echo "                        <label class=\"text-light bg-danger center-block\">";
                echo twig_escape_filter($this->env, ($context["response"] ?? null), "html", null, true);
                echo "</label>
                    ";
            }
            // line 29
            echo "                 </div>
             </div>
        ";
        }
        // line 32
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
        return array (  80 => 32,  75 => 29,  69 => 27,  63 => 25,  61 => 24,  57 => 22,  55 => 21,  35 => 3,  32 => 2,  15 => 1,);
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

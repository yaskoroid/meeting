<?php

/* home.tpl */
class __TwigTemplate_ae05c89e9fb7bbfa4521cdbd77ef168cd6f20856ace7254eabf1db74bc8adfed extends Twig_Template
{
    private $source;

    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        // line 1
        $this->parent = $this->loadTemplate("base.tpl", "home.tpl", 1);
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
        if ((($context["user"] ?? null) == null)) {
            // line 4
            echo "    <div class=\"container\">
        <div>Приложение-задачник для быстрого доступа и контроля заданий пользователей</div>
        <div class=\"form-group\">
            <div class=\"col-sm-5\">
                <a class=\"btn btn-lg btn-primary btn-block\" href=\"/about\">О приложении</a>
            </div>
        </div></div>
";
        } else {
            // line 12
            echo "    <div class =\"container\">
        <div class=\"form-horizontal\">
            <div>Приложение-задачник для быстрого доступа и контроля заданий пользователей</div>
            <div class=\"form-group\">
                <div class=\"col-sm-12\">
                    <button class=\"btn btn-lg btn-primary btn-block\">Посмотреть все задачи</button>
                </div>
            </div>
        </div>
    </div>
";
        }
    }

    public function getTemplateName()
    {
        return "home.tpl";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  47 => 12,  37 => 4,  35 => 3,  32 => 2,  15 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("{% extends \"base.tpl\" %}
{% block pageContent %}
{% if user == null%}
    <div class=\"container\">
        <div>Приложение-задачник для быстрого доступа и контроля заданий пользователей</div>
        <div class=\"form-group\">
            <div class=\"col-sm-5\">
                <a class=\"btn btn-lg btn-primary btn-block\" href=\"/about\">О приложении</a>
            </div>
        </div></div>
{% else %}
    <div class =\"container\">
        <div class=\"form-horizontal\">
            <div>Приложение-задачник для быстрого доступа и контроля заданий пользователей</div>
            <div class=\"form-group\">
                <div class=\"col-sm-12\">
                    <button class=\"btn btn-lg btn-primary btn-block\">Посмотреть все задачи</button>
                </div>
            </div>
        </div>
    </div>
{% endif %}
{% endblock %}", "home.tpl", "C:\\Program Files (x86)\\Ampps\\www\\roman.com\\application\\template\\View\\home.tpl");
    }
}

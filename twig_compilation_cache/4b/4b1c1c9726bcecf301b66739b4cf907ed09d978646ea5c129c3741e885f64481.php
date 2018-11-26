<?php

/* user.tpl */
class __TwigTemplate_4c37d191ed8421a16825b10599a277b70bc948e4db080d6300d69ba62d3f4769 extends Twig_Template
{
    private $source;

    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        // line 1
        $this->parent = $this->loadTemplate("base.tpl", "user.tpl", 1);
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
        echo "<script type=\"text/javascript\" src=\"/js/controller/user.js\"></script>
<div class=\"container\">
    <div class=\"starter-template\">
        <!-- Содержимое страницы в форме -->
        <form name=\"formTasks\" method=\"post\" enctype=\"multipart/form-data\" class=\"js-main-form\">
        </form>
    </div>
</div>
";
    }

    public function getTemplateName()
    {
        return "user.tpl";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  35 => 3,  32 => 2,  15 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("{% extends \"base.tpl\" %}
{% block pageContent %}
<script type=\"text/javascript\" src=\"/js/controller/user.js\"></script>
<div class=\"container\">
    <div class=\"starter-template\">
        <!-- Содержимое страницы в форме -->
        <form name=\"formTasks\" method=\"post\" enctype=\"multipart/form-data\" class=\"js-main-form\">
        </form>
    </div>
</div>
{% endblock %}
", "user.tpl", "C:\\Program Files (x86)\\Ampps\\www\\roman.com\\application\\template\\View\\user.tpl");
    }
}

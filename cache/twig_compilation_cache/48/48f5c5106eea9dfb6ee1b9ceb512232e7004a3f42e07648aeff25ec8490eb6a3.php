<?php

/* task.tpl */
class __TwigTemplate_36c7b45aa6e14529215e0bb06ca11da0bdf3735bc3f61b74ee89d2e2200a001d extends Twig_Template
{
    private $source;

    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        // line 1
        $this->parent = $this->loadTemplate("base.tpl", "task.tpl", 1);
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
    <form action=\"\" name=\"taskForm\" method=\"post\" class=\"form-horizontal\">
        <div class=\"form-group col-sm-12\">
            <h2 class=\"form-signin-heading\">Задачи</h2>
        </div>
    </form>
</div>
";
    }

    public function getTemplateName()
    {
        return "task.tpl";
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
<script type=\"text/javascript\" src=\"/js/controller/login.js\"></script>
<div class=\"container\">
    <form action=\"\" name=\"taskForm\" method=\"post\" class=\"form-horizontal\">
        <div class=\"form-group col-sm-12\">
            <h2 class=\"form-signin-heading\">Задачи</h2>
        </div>
    </form>
</div>
{% endblock %}", "task.tpl", "C:\\Program Files (x86)\\Ampps\\www\\roman.com\\application\\template\\View\\task.tpl");
    }
}

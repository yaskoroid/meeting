<?php

/* error404.tpl */
class __TwigTemplate_d3e0787cbfe98396b93ec8b912c21b9600c611c49fe100a83572124aa5fb60f0 extends Twig_Template
{
    private $source;

    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        // line 1
        $this->parent = $this->loadTemplate("base.tpl", "error404.tpl", 1);
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
        echo "<div class=\"container\">
    <h1>
        Ошибка 404
    </h1>

    <h2>
        Страница на найдена!
    </h2>

    <div class=\"col-md-12\">
        <img class=\"center-block img-responsive\" src=\"";
        // line 13
        echo twig_escape_filter($this->env, ($context["image404FilePath"] ?? null), "html", null, true);
        echo "\">
    </div>
</div>
";
    }

    public function getTemplateName()
    {
        return "error404.tpl";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  47 => 13,  35 => 3,  32 => 2,  15 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("{% extends \"base.tpl\" %}
{% block pageContent %}
<div class=\"container\">
    <h1>
        Ошибка 404
    </h1>

    <h2>
        Страница на найдена!
    </h2>

    <div class=\"col-md-12\">
        <img class=\"center-block img-responsive\" src=\"{{image404FilePath}}\">
    </div>
</div>
{% endblock %}", "error404.tpl", "C:\\Program Files (x86)\\Ampps\\www\\roman.com\\application\\template\\View\\error404.tpl");
    }
}

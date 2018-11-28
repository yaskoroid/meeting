<?php

/* change_confirm.tpl */
class __TwigTemplate_618784ee1582cba3cd768cffdcbeef8117052a5e1a234c6367b98e9426083384 extends Twig_Template
{
    private $source;

    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        // line 1
        $this->parent = $this->loadTemplate("base.tpl", "change_confirm.tpl", 1);
        $this->blocks = array(
            'emailContent' => array($this, 'block_emailContent'),
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
    public function block_emailContent($context, array $blocks = array())
    {
        // line 3
        echo "<h1>";
        echo twig_escape_filter($this->env, ($context["title"] ?? null), "html", null, true);
        echo "</h1>
<form method=\"";
        // line 4
        echo twig_escape_filter($this->env, ($context["method"] ?? null), "html", null, true);
        echo "\" action=\"";
        echo twig_escape_filter($this->env, ($context["action"] ?? null), "html", null, true);
        echo "\">
    <p>";
        // line 5
        echo twig_escape_filter($this->env, ($context["text"] ?? null), "html", null, true);
        echo "</p>
    <a href=\"";
        // line 6
        echo twig_escape_filter($this->env, ($context["action"] ?? null), "html", null, true);
        echo "\">Продолжить на сайте</a>
</form>
";
    }

    public function getTemplateName()
    {
        return "change_confirm.tpl";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  50 => 6,  46 => 5,  40 => 4,  35 => 3,  32 => 2,  15 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("{% extends \"base.tpl\" %}
{% block emailContent %}
<h1>{{ title }}</h1>
<form method=\"{{ method }}\" action=\"{{ action }}\">
    <p>{{ text }}</p>
    <a href=\"{{action}}\">Продолжить на сайте</a>
</form>
{% endblock %}", "change_confirm.tpl", "C:\\Program Files (x86)\\Ampps\\www\\roman.com\\application\\template\\Email\\change_confirm.tpl");
    }
}

<?php

/* base.tpl */
class __TwigTemplate_554b565fd87dde74dd879b7b9f3c64936dfcfc49bc8f556752df9b893203c2d0 extends Twig_Template
{
    private $source;

    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = array(
            'emailContent' => array($this, 'block_emailContent'),
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "<!DOCTYPE html>
<head>
    <meta charset=\"UTF-8\">
    <title>Contact form</title>
    <style>";
        // line 5
        echo twig_escape_filter($this->env, ($context["style"] ?? null), "html", null, true);
        echo "</style>
<body>
";
        // line 7
        $this->displayBlock('emailContent', $context, $blocks);
        // line 10
        echo "</body>
</head>
";
    }

    // line 7
    public function block_emailContent($context, array $blocks = array())
    {
        // line 8
        echo "<div>No email content, sorry</div>
";
    }

    public function getTemplateName()
    {
        return "base.tpl";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  46 => 8,  43 => 7,  37 => 10,  35 => 7,  30 => 5,  24 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "base.tpl", "C:\\Program Files (x86)\\Ampps\\www\\roman.com\\application\\template\\Email\\base.tpl");
    }
}

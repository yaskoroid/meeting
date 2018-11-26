<?php

/* user_change_password_confirm.tpl */
class __TwigTemplate_25c35f163807778f10bfb453c8ebc0be0b156e6d6304b1fd3ac721cc746e6313 extends Twig_Template
{
    private $source;

    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        // line 1
        $this->parent = $this->loadTemplate("base.tpl", "user_change_password_confirm.tpl", 1);
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
        echo "\">Подтерждение</a>
    <button type=\"submit\" class=\"btn btn-success\">Подтерждение</button><br>
</form>
";
    }

    public function getTemplateName()
    {
        return "user_change_password_confirm.tpl";
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
        return new Twig_Source("", "user_change_password_confirm.tpl", "C:\\Program Files (x86)\\Ampps\\www\\roman.com\\application\\template\\Email\\user_change_password_confirm.tpl");
    }
}

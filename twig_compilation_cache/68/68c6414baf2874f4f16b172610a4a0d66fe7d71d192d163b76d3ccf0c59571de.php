<?php

/* User.tpl */
class __TwigTemplate_328d39b68f629d6d12faef4033a41326ec6b25330e142195e830a2a5149f85b8 extends Twig_Template
{
    private $source;

    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        // line 1
        $this->parent = $this->loadTemplate("Base.tpl", "User.tpl", 1);
        $this->blocks = array(
            'pageContent' => array($this, 'block_pageContent'),
        );
    }

    protected function doGetParent(array $context)
    {
        return "Base.tpl";
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 2
    public function block_pageContent($context, array $blocks = array())
    {
        // line 3
        echo "<script type=\"text/javascript\" src=\"/js/users/users.js\"></script>
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
        return "User.tpl";
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
        return new Twig_Source("", "User.tpl", "C:\\Program Files (x86)\\Ampps\\www\\roman.com\\application\\template\\View\\User.tpl");
    }
}

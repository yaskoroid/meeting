<?php

/* error404.tpl */
class __TwigTemplate_f73fb77f70bc2cb0160e9a239aa3fef2e132915391f0a49aabfafb934b15362d extends Twig_Template
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
        <img class=\"center-block img-responsive\" src=\"/images/etc/404.jpg\">
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
        return array (  35 => 3,  32 => 2,  15 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "error404.tpl", "C:\\Program Files (x86)\\Ampps\\www\\roman.com\\application\\template\\View\\error404.tpl");
    }
}

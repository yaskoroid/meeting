<?php

/* settings.tpl */
class __TwigTemplate_e21090b78669b4209791d1ef87bc5b076aa66377753f74c3561d78302a7dab01 extends Twig_Template
{
    private $source;

    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        // line 1
        $this->parent = $this->loadTemplate("base.tpl", "settings.tpl", 1);
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
        echo "<script type=\"text/javascript\" src=\"/js/controller/settings.js\"></script>
<script data-require=\"MomentJS@2.10.0\" data-semver=\"2.10.0\" src=\"https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js\"></script>
<script src=\"https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.1/js/tempusdominus-bootstrap-4.min.js\"></script>
<link rel=\"stylesheet\" href=\"https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.1/css/tempusdominus-bootstrap-4.min.css\" />
<script src=\"https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/locale/ru.js\"></script>

<div class=\"container\">
    <form action=\"\" name=\"settingsForm\" method=\"post\" class=\"form-horizontal\">
        <div class=\"form-group col-sm-12\">
            <h2 class=\"form-signin-heading\">Настройки</h2>
            <div class=\"js-content\"></div>
        </div>
    </form>
</div>
";
    }

    public function getTemplateName()
    {
        return "settings.tpl";
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
<script type=\"text/javascript\" src=\"/js/controller/settings.js\"></script>
<script data-require=\"MomentJS@2.10.0\" data-semver=\"2.10.0\" src=\"https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js\"></script>
<script src=\"https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.1/js/tempusdominus-bootstrap-4.min.js\"></script>
<link rel=\"stylesheet\" href=\"https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.1/css/tempusdominus-bootstrap-4.min.css\" />
<script src=\"https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/locale/ru.js\"></script>

<div class=\"container\">
    <form action=\"\" name=\"settingsForm\" method=\"post\" class=\"form-horizontal\">
        <div class=\"form-group col-sm-12\">
            <h2 class=\"form-signin-heading\">Настройки</h2>
            <div class=\"js-content\"></div>
        </div>
    </form>
</div>
{% endblock %}", "settings.tpl", "C:\\Program Files (x86)\\Ampps\\www\\roman.com\\application\\template\\View\\settings.tpl");
    }
}

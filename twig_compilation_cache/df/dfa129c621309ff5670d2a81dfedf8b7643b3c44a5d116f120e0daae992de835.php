<?php

/* confirm.tpl */
class __TwigTemplate_ef41723b8d284f86a094ef470e3e63b09e25e22cc2405b3abd3d7f2b4c4421ca extends Twig_Template
{
    private $source;

    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        // line 1
        $this->parent = $this->loadTemplate("base.tpl", "confirm.tpl", 1);
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
    <form action=\"";
        // line 4
        echo twig_escape_filter($this->env, ($context["action"] ?? null), "html", null, true);
        echo "\" name=\"confirmForm\" method=\"";
        echo twig_escape_filter($this->env, ($context["method"] ?? null), "html", null, true);
        echo "\" class=\"form-horizontal\">
        <div class=\"form-group col-sm-12\">
            <h2 class=\"form-signin-heading\">";
        // line 6
        echo twig_escape_filter($this->env, ($context["header"] ?? null), "html", null, true);
        echo "</h2>
        </div>
        ";
        // line 8
        if ( !twig_test_empty(($context["inputs"] ?? null))) {
            // line 9
            echo "            ";
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(($context["inputs"] ?? null));
            foreach ($context['_seq'] as $context["_key"] => $context["input"]) {
                // line 10
                echo "            <div class=\"form-group\">
                <label class=\"control-label col-sm-3\">";
                // line 11
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["input"], "text", array()), "html", null, true);
                echo "</label>
                <div class=\"col-sm-9\">
                    <input type=\"text\" name=\"";
                // line 13
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["input"], "name", array()), "html", null, true);
                echo "\" class=\"form-control\" placeholder=\"";
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["input"], "placeholder", array()), "html", null, true);
                echo "\" required";
                echo ((twig_get_attribute($this->env, $this->source, $context["input"], "autofocus", array())) ? (" autofocus") : (""));
                echo "\">
                </div>
            </div>
            ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['input'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 17
            echo "        ";
        }
        // line 18
        echo "        ";
        if (($context["response"] ?? null)) {
            // line 19
            echo "        <div class=\"form-group\">
            <div class=\"col-sm-12\">
                ";
            // line 21
            if ((twig_get_attribute($this->env, $this->source, ($context["response"] ?? null), "error", array()) == null)) {
                // line 22
                echo "                <label class=\"text-light bg-success center-block\">";
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["response"] ?? null), "responseText", array()), "html", null, true);
                echo "</label>
                ";
            } else {
                // line 24
                echo "                <label class=\"text-light bg-danger center-block\">";
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["response"] ?? null), "responseText", array()), "html", null, true);
                echo "</label>
                ";
            }
            // line 26
            echo "            </div>
        </div>
        ";
        }
        // line 29
        echo "        ";
        if (($context["submit"] ?? null)) {
            // line 30
            echo "        <div class=\"form-group\">
            <div class=\"col-sm-12\">
                <button class=\"btn btn-lg btn-primary btn-block\" type=\"submit\" name\"";
            // line 32
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["submit"] ?? null), "submitName", array()), "html", null, true);
            echo "\">";
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["submit"] ?? null), "submitText", array()), "html", null, true);
            echo "</button>
            </div>
        </div>
        ";
        }
        // line 36
        echo "
    </form>
</div>
";
    }

    public function getTemplateName()
    {
        return "confirm.tpl";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  124 => 36,  115 => 32,  111 => 30,  108 => 29,  103 => 26,  97 => 24,  91 => 22,  89 => 21,  85 => 19,  82 => 18,  79 => 17,  65 => 13,  60 => 11,  57 => 10,  52 => 9,  50 => 8,  45 => 6,  38 => 4,  35 => 3,  32 => 2,  15 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("{% extends \"base.tpl\" %}
{% block pageContent %}
<div class=\"container\">
    <form action=\"{{action}}\" name=\"confirmForm\" method=\"{{method}}\" class=\"form-horizontal\">
        <div class=\"form-group col-sm-12\">
            <h2 class=\"form-signin-heading\">{{header}}</h2>
        </div>
        {% if inputs is not empty %}
            {%for input in inputs%}
            <div class=\"form-group\">
                <label class=\"control-label col-sm-3\">{{input.text}}</label>
                <div class=\"col-sm-9\">
                    <input type=\"text\" name=\"{{input.name}}\" class=\"form-control\" placeholder=\"{{input.placeholder}}\" required{{ input.autofocus ? ' autofocus' : '' }}\">
                </div>
            </div>
            {%endfor%}
        {% endif %}
        {% if response %}
        <div class=\"form-group\">
            <div class=\"col-sm-12\">
                {% if response.error == null %}
                <label class=\"text-light bg-success center-block\">{{ response.responseText }}</label>
                {% else %}
                <label class=\"text-light bg-danger center-block\">{{ response.responseText }}</label>
                {% endif %}
            </div>
        </div>
        {% endif %}
        {% if submit %}
        <div class=\"form-group\">
            <div class=\"col-sm-12\">
                <button class=\"btn btn-lg btn-primary btn-block\" type=\"submit\" name\"{{submit.submitName}}\">{{submit.submitText}}</button>
            </div>
        </div>
        {% endif %}

    </form>
</div>
{% endblock %}", "confirm.tpl", "C:\\Program Files (x86)\\Ampps\\www\\roman.com\\application\\template\\View\\confirm.tpl");
    }
}

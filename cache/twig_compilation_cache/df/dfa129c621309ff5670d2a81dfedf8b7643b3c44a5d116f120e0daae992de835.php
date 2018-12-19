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
        echo "<script type=\"text/javascript\" src=\"/js/controller/confirm.js\"></script>
";
        // line 4
        if ( !twig_test_empty(($context["intent"] ?? null))) {
            // line 5
            echo "<!-- Intent value -->
<script type=\"text/javascript\">
    ACTION_INTENT = \"";
            // line 7
            echo twig_escape_filter($this->env, ($context["intent"] ?? null), "html", null, true);
            echo "\";
</script>
";
        }
        // line 10
        echo "<div class=\"container\">
    <form action=\"\" name=\"confirmForm\" method=\"post\" class=\"form-horizontal\">
        <div class=\"form-group col-sm-12\">
            <h2 class=\"form-signin-heading\">";
        // line 13
        echo twig_escape_filter($this->env, ($context["header"] ?? null), "html", null, true);
        echo "</h2>
        </div>
        ";
        // line 15
        if ( !twig_test_empty(($context["inputs"] ?? null))) {
            // line 16
            echo "            ";
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(($context["inputs"] ?? null));
            foreach ($context['_seq'] as $context["_key"] => $context["input"]) {
                // line 17
                echo "            <div class=\"form-group\">
                <label class=\"control-label col-sm-12\">";
                // line 18
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["input"], "text", array()), "html", null, true);
                echo "</label>
                <div class=\"col-sm-9\">
                    <input type=\"text\" name=\"";
                // line 20
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
            // line 24
            echo "        ";
        }
        // line 25
        echo "        ";
        if (($context["response"] ?? null)) {
            // line 26
            echo "        <div class=\"form-group\">
            <div class=\"col-sm-12\">
                ";
            // line 28
            if ((twig_get_attribute($this->env, $this->source, ($context["response"] ?? null), "error", array()) == null)) {
                // line 29
                echo "                <label class=\"text-light bg-success center-block\">";
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["response"] ?? null), "responseText", array()), "html", null, true);
                echo "</label>
                ";
            } else {
                // line 31
                echo "                <label class=\"text-light bg-danger center-block\">";
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["response"] ?? null), "responseText", array()), "html", null, true);
                echo "</label>
                ";
            }
            // line 33
            echo "            </div>
        </div>
        ";
        }
        // line 36
        echo "        ";
        if (($context["cancel"] ?? null)) {
            // line 37
            echo "        <div class=\"form-group\">
            <div class=\"col-sm-12\">
                <button class=\"btn btn-lg btn-danger btn-block\" type=\"cancel\" name=\"";
            // line 39
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["cancel"] ?? null), "name", array()), "html", null, true);
            echo "\">";
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["cancel"] ?? null), "text", array()), "html", null, true);
            echo "</button>
            </div>
        </div>
        ";
        }
        // line 43
        echo "        ";
        if (($context["submit"] ?? null)) {
            // line 44
            echo "        <div class=\"form-group\">
            <div class=\"col-sm-12\">
                <button class=\"btn btn-lg btn-primary btn-block\" type=\"submit\" name=\"";
            // line 46
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["submit"] ?? null), "name", array()), "html", null, true);
            echo "\">";
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["submit"] ?? null), "text", array()), "html", null, true);
            echo "</button>
            </div>
        </div>
        ";
        }
        // line 50
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
        return array (  150 => 50,  141 => 46,  137 => 44,  134 => 43,  125 => 39,  121 => 37,  118 => 36,  113 => 33,  107 => 31,  101 => 29,  99 => 28,  95 => 26,  92 => 25,  89 => 24,  75 => 20,  70 => 18,  67 => 17,  62 => 16,  60 => 15,  55 => 13,  50 => 10,  44 => 7,  40 => 5,  38 => 4,  35 => 3,  32 => 2,  15 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("{% extends \"base.tpl\" %}
{% block pageContent %}
<script type=\"text/javascript\" src=\"/js/controller/confirm.js\"></script>
{% if intent is not empty %}
<!-- Intent value -->
<script type=\"text/javascript\">
    ACTION_INTENT = \"{{ intent }}\";
</script>
{% endif %}
<div class=\"container\">
    <form action=\"\" name=\"confirmForm\" method=\"post\" class=\"form-horizontal\">
        <div class=\"form-group col-sm-12\">
            <h2 class=\"form-signin-heading\">{{header}}</h2>
        </div>
        {% if inputs is not empty %}
            {%for input in inputs%}
            <div class=\"form-group\">
                <label class=\"control-label col-sm-12\">{{input.text}}</label>
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
        {% if cancel %}
        <div class=\"form-group\">
            <div class=\"col-sm-12\">
                <button class=\"btn btn-lg btn-danger btn-block\" type=\"cancel\" name=\"{{cancel.name}}\">{{cancel.text}}</button>
            </div>
        </div>
        {% endif %}
        {% if submit %}
        <div class=\"form-group\">
            <div class=\"col-sm-12\">
                <button class=\"btn btn-lg btn-primary btn-block\" type=\"submit\" name=\"{{submit.name}}\">{{submit.text}}</button>
            </div>
        </div>
        {% endif %}

    </form>
</div>
{% endblock %}", "confirm.tpl", "C:\\Program Files (x86)\\Ampps\\www\\roman.com\\application\\template\\View\\confirm.tpl");
    }
}

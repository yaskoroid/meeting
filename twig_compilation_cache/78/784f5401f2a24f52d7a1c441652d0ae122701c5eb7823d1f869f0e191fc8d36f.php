<?php

/* login.tpl */
class __TwigTemplate_4ddaed33644261a7a2a9481b6b49242a217731e63de471e13ceba195137d12c7 extends Twig_Template
{
    private $source;

    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        // line 1
        $this->parent = $this->loadTemplate("Base.tpl", "login.tpl", 1);
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
        echo "<div class=\"container\">
    <form action=\"\" name=\"form\" method=\"post\" class=\"form-horizontal\">
        <div class=\"form-group col-sm-12\">
            <h2 class=\"form-signin-heading\">Авторизация</h2>
        </div>
        <div class=\"form-group\">
            <label for=\"inputEmail\" class=\"control-label col-sm-3\">Пользователь</label>
            <div class=\"col-sm-9\">
                <input type=\"text\" name=\"login\" class=\"form-control\" placeholder=\"Логин\" required autofocus>
            </div>
        </div>
        <div class=\"form-group\">
            <label for=\"inputPassword\" class=\"control-label col-sm-3\">Пароль</label>
            <div class=\"col-sm-9\">
                <input type=\"password\" name=\"password\" class=\"form-control\" placeholder=\"Пароль\" required>
            </div>
        </div>
        ";
        // line 20
        if (($context["response"] ?? null)) {
            // line 21
            echo "            <div class=\"form-group\">
                <div class=\"col-sm-12\">
                    ";
            // line 23
            if ((($context["error"] ?? null) == null)) {
                // line 24
                echo "                        <label class=\"text-light bg-success center-block\">";
                echo twig_escape_filter($this->env, ($context["response"] ?? null), "html", null, true);
                echo "</label>
                    ";
            } else {
                // line 26
                echo "                        <label class=\"text-light bg-danger center-block\">";
                echo twig_escape_filter($this->env, ($context["response"] ?? null), "html", null, true);
                echo "</label>
                    ";
            }
            // line 28
            echo "                 </div>
             </div>
        ";
        }
        // line 31
        echo "        <div class=\"form-group\">
            <div class=\"col-sm-12\">
            <button class=\"btn btn-lg btn-primary btn-block\" type=\"submit\">Войти</button>
            </div>
        </div>
    </form>
</div>
";
    }

    public function getTemplateName()
    {
        return "login.tpl";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  79 => 31,  74 => 28,  68 => 26,  62 => 24,  60 => 23,  56 => 21,  54 => 20,  35 => 3,  32 => 2,  15 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "login.tpl", "C:\\Program Files (x86)\\Ampps\\www\\roman.com\\application\\template\\View\\login.tpl");
    }
}

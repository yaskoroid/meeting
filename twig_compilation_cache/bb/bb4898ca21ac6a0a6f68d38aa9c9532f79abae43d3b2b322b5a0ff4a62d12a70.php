<?php

/* Base.tpl */
class __TwigTemplate_298d82c9125fd0ded1ef243d1ccdc6cc7c931b631435a25e7313c5c1d9248823 extends Twig_Template
{
    private $source;

    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = array(
            'pageContent' => array($this, 'block_pageContent'),
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "<!DOCTYPE html>
<html>
<head>
    <meta charset=\"utf-8\">
    ";
        // line 5
        if ( !twig_test_empty(($context["def"] ?? null))) {
            // line 6
            echo "    <!-- Defaults -->
    <script type=\"text/javascript\">
        ";
            // line 8
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(twig_get_array_keys_filter(($context["def"] ?? null)));
            foreach ($context['_seq'] as $context["_key"] => $context["defItemKey"]) {
                // line 9
                echo "        ";
                echo twig_escape_filter($this->env, $context["defItemKey"], "html", null, true);
                echo " = \"";
                echo twig_escape_filter($this->env, (($__internal_7cd7461123377b8c9c1b6a01f46c7bbd94bd12e59266005df5e93029ddbc0ec5 = ($context["def"] ?? null)) && is_array($__internal_7cd7461123377b8c9c1b6a01f46c7bbd94bd12e59266005df5e93029ddbc0ec5) || $__internal_7cd7461123377b8c9c1b6a01f46c7bbd94bd12e59266005df5e93029ddbc0ec5 instanceof ArrayAccess ? ($__internal_7cd7461123377b8c9c1b6a01f46c7bbd94bd12e59266005df5e93029ddbc0ec5[$context["defItemKey"]] ?? null) : null), "html", null, true);
                echo "\";
        ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['defItemKey'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 11
            echo "    </script>
    ";
        }
        // line 13
        echo "    <!-- Latest compiled and minified CSS -->
    <link rel=\"stylesheet\" href=\"https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css\"
          integrity=\"sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB\" crossorigin=\"anonymous\">
    <!-- Bootstrap needs jQuery, so download it -->
    <script src=\"https://code.jquery.com/jquery-3.3.1.slim.min.js\"
            integrity=\"sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo\" crossorigin=\"anonymous\">
    </script>
    <script src=\"http://code.jquery.com/jquery-latest.min.js\"></script>
    <!-- Latest compiled and minified JavaScript -->
    <script src=\"https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js\"
            integrity=\"sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T\" crossorigin=\"anonymous\">
    </script>
    <script src=\"/js/bootstrap/notify/bootstrap-notify.min.js\"></script>
    <!-- Font Awesome -->
    <link rel='stylesheet' href='https://use.fontawesome.com/releases/v5.4.1/css/all.css' integrity='sha384-5sAR7xN1Nv6T6+dT2mhtzEpVJvfS3NScPQTrOxhwjIuvcA67KV2R5Jz6kr4abQsz' crossorigin='anonymous'>
    <link rel=\"stylesheet\" href=\"/css/style.css\">
    <link rel=\"shortcut icon\" type=\"image/x-icon\" href=\"/favicon.ico\">
    <script type=\"text/javascript\" src=\"/js/script.js\"></script>
    <title>";
        // line 31
        echo twig_escape_filter($this->env, ($context["title"] ?? null), "html", null, true);
        echo "</title>
    <meta name=\"description\" content=\"";
        // line 32
        echo twig_escape_filter($this->env, ($context["description"] ?? null), "html", null, true);
        echo "\">
    <meta name=\"keywords\" content=\"";
        // line 33
        echo twig_escape_filter($this->env, ($context["keywords"] ?? null), "html", null, true);
        echo "\">
</head>
<body>
<!-- Меню -->
<nav class=\"navbar navbar-expand-lg navbar-dark bg-dark\">
    <div class=\"container\">
        <a class=\"navbar-brand\" href=\"/users\">Собрание</a>
        <button class=\"navbar-toggler\" type=\"button\" data-toggle=\"collapse\" data-target=\"#navbar\" aria-controls=\"navbar\" aria-expanded=\"false\" aria-label=\"Toggle navigation\">
            <span class=\"navbar-toggler-icon\"></span>
        </button>

        <div class=\"collapse navbar-collapse\" id=\"navbar\" aria-expanded=\"false\" style=\"height: 1px;\" role=\"menu\">
            <ul class=\"navbar-nav mr-auto\">
                <li class=\"nav-item";
        // line 46
        echo (((($context["page"] ?? null) == "Home")) ? (" active") : (""));
        echo "\"><a class=\"nav-link\" href=\"/home\">Дом</a></li>
                <li class=\"nav-item";
        // line 47
        echo (((($context["page"] ?? null) == "User")) ? (" active") : (""));
        echo "\"><a class=\"nav-link\" href=\"/user\">Пользователи</a></li>
                <li class=\"nav-item";
        // line 48
        echo (((($context["page"] ?? null) == "About")) ? (" active") : (""));
        echo "\"><a class=\"nav-link\" href=\"/about\">О приложении</a></li>
                <li class=\"nav-item";
        // line 49
        echo (((($context["page"] ?? null) == "Login")) ? (" active") : (""));
        echo "\"><a class=\"nav-link\" href=\"/login\">Войти</a></li>
            </ul>
            <ul class=\"navbar-nav navbar-right\">
                ";
        // line 52
        if (($context["user"] ?? null)) {
            // line 53
            echo "                    <li class=\"nav-item\"><p>";
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["user"] ?? null), "login", array()), "html", null, true);
            echo "</p></li>
                    <li class=\"nav-item\"><a class=\"nav-link\" href=\"/logout\">Выйти</a></li>
                ";
        } else {
            // line 56
            echo "                    <li class=\"nav-item\" id=\"label\">Авторизируйтесь</li>
                ";
        }
        // line 58
        echo "            </ul>
        </div>
    </div>
</nav>
<!-- Подключаем конкретную страницу (экшн) -->
";
        // line 63
        $this->displayBlock('pageContent', $context, $blocks);
        // line 66
        echo "</body>
</html>";
    }

    // line 63
    public function block_pageContent($context, array $blocks = array())
    {
        // line 64
        echo "<div>No page content</div>
";
    }

    public function getTemplateName()
    {
        return "Base.tpl";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  147 => 64,  144 => 63,  139 => 66,  137 => 63,  130 => 58,  126 => 56,  119 => 53,  117 => 52,  111 => 49,  107 => 48,  103 => 47,  99 => 46,  83 => 33,  79 => 32,  75 => 31,  55 => 13,  51 => 11,  40 => 9,  36 => 8,  32 => 6,  30 => 5,  24 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "Base.tpl", "C:\\Program Files (x86)\\Ampps\\www\\roman.com\\application\\template\\View\\Base.tpl");
    }
}

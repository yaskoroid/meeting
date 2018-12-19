<?php

/* base.tpl */
class __TwigTemplate_06811f88c513383c22bd4b45c2079930c83b2ebe49c1d10c9cdd2faceb373093 extends Twig_Template
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
                echo "            ";
                if (twig_test_iterable((($__internal_7cd7461123377b8c9c1b6a01f46c7bbd94bd12e59266005df5e93029ddbc0ec5 = ($context["def"] ?? null)) && is_array($__internal_7cd7461123377b8c9c1b6a01f46c7bbd94bd12e59266005df5e93029ddbc0ec5) || $__internal_7cd7461123377b8c9c1b6a01f46c7bbd94bd12e59266005df5e93029ddbc0ec5 instanceof ArrayAccess ? ($__internal_7cd7461123377b8c9c1b6a01f46c7bbd94bd12e59266005df5e93029ddbc0ec5[$context["defItemKey"]] ?? null) : null))) {
                    // line 10
                    echo "                ";
                    echo twig_escape_filter($this->env, $context["defItemKey"], "html", null, true);
                    echo " = JSON.parse('";
                    echo json_encode((($__internal_3e28b7f596c58d7729642bcf2acc6efc894803703bf5fa7e74cd8d2aa1f8c68a = ($context["def"] ?? null)) && is_array($__internal_3e28b7f596c58d7729642bcf2acc6efc894803703bf5fa7e74cd8d2aa1f8c68a) || $__internal_3e28b7f596c58d7729642bcf2acc6efc894803703bf5fa7e74cd8d2aa1f8c68a instanceof ArrayAccess ? ($__internal_3e28b7f596c58d7729642bcf2acc6efc894803703bf5fa7e74cd8d2aa1f8c68a[$context["defItemKey"]] ?? null) : null));
                    echo "');
            ";
                } else {
                    // line 12
                    echo "                ";
                    echo twig_escape_filter($this->env, $context["defItemKey"], "html", null, true);
                    echo " = \"";
                    echo twig_escape_filter($this->env, (($__internal_b0b3d6199cdf4d15a08b3fb98fe017ecb01164300193d18d78027218d843fc57 = ($context["def"] ?? null)) && is_array($__internal_b0b3d6199cdf4d15a08b3fb98fe017ecb01164300193d18d78027218d843fc57) || $__internal_b0b3d6199cdf4d15a08b3fb98fe017ecb01164300193d18d78027218d843fc57 instanceof ArrayAccess ? ($__internal_b0b3d6199cdf4d15a08b3fb98fe017ecb01164300193d18d78027218d843fc57[$context["defItemKey"]] ?? null) : null), "html", null, true);
                    echo "\";
            ";
                }
                // line 14
                echo "        ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['defItemKey'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 15
            echo "    </script>
    ";
        }
        // line 17
        echo "    ";
        if ( !twig_test_empty(($context["frontendConstants"] ?? null))) {
            // line 18
            echo "    <!-- Frontend constants -->
    <script type=\"text/javascript\">
        ";
            // line 20
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(twig_get_array_keys_filter(($context["frontendConstants"] ?? null)));
            foreach ($context['_seq'] as $context["_key"] => $context["frontendConstantsItemKey"]) {
                // line 21
                echo "        ";
                echo twig_escape_filter($this->env, $context["frontendConstantsItemKey"], "html", null, true);
                echo " = \"";
                echo twig_escape_filter($this->env, (($__internal_81ccf322d0988ca0aa9ae9943d772c435c5ff01fb50b956278e245e40ae66ab9 = ($context["frontendConstants"] ?? null)) && is_array($__internal_81ccf322d0988ca0aa9ae9943d772c435c5ff01fb50b956278e245e40ae66ab9) || $__internal_81ccf322d0988ca0aa9ae9943d772c435c5ff01fb50b956278e245e40ae66ab9 instanceof ArrayAccess ? ($__internal_81ccf322d0988ca0aa9ae9943d772c435c5ff01fb50b956278e245e40ae66ab9[$context["frontendConstantsItemKey"]] ?? null) : null), "html", null, true);
                echo "\";
        ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['frontendConstantsItemKey'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 23
            echo "    </script>
    ";
        }
        // line 25
        echo "    <!-- Latest compiled and minified CSS -->
    <link rel=\"stylesheet\" href=\"https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css\"
          integrity=\"sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB\" crossorigin=\"anonymous\">
    <!-- Bootstrap needs jQuery, so download it -->
    <script type=\"text/javascript\" src=\"https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js\"></script>
    <!--<script src=\"https://code.jquery.com/jquery-3.3.1.slim.min.js\"
             integrity=\"sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo\" crossorigin=\"anonymous\">
     </script>*}
     <script src=\"http://code.jquery.com/jquery-latest.min.js\"></script>-->
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
        // line 44
        echo twig_escape_filter($this->env, ($context["title"] ?? null), "html", null, true);
        echo "</title>
    <meta name=\"description\" content=\"";
        // line 45
        echo twig_escape_filter($this->env, ($context["description"] ?? null), "html", null, true);
        echo "\">
    <meta name=\"keywords\" content=\"";
        // line 46
        echo twig_escape_filter($this->env, ($context["keywords"] ?? null), "html", null, true);
        echo "\">
</head>
<body>
<!-- Меню -->
<nav class=\"navbar navbar-expand-lg navbar-dark bg-dark\">
    <div class=\"container\">
        <a class=\"navbar-brand\" href=\"/\">Собрание</a>
        <button class=\"navbar-toggler\" type=\"button\" data-toggle=\"collapse\" data-target=\"#navbar\" aria-controls=\"navbar\" aria-expanded=\"false\" aria-label=\"Toggle navigation\">
            <span class=\"navbar-toggler-icon\"></span>
        </button>

        <div class=\"collapse navbar-collapse\" id=\"navbar\" aria-expanded=\"false\" style=\"height: 1px;\" role=\"menu\">
            <ul class=\"navbar-nav mr-auto\">
                <li class=\"nav-item";
        // line 59
        echo (((($context["page"] ?? null) == "Home")) ? (" active") : (""));
        echo "\"><a class=\"nav-link\" href=\"/home\">Дом</a></li>
                ";
        // line 60
        if (($context["user"] ?? null)) {
            // line 61
            echo "                    <li class=\"nav-item";
            echo (((($context["page"] ?? null) == "User")) ? (" active") : (""));
            echo "\"><a class=\"nav-link\" href=\"/user\">Пользователи</a></li>
                    ";
            // line 62
            if ((($context["userType"] ?? null) && (twig_get_attribute($this->env, $this->source, ($context["userType"] ?? null), "role", array()) != "customer"))) {
                // line 63
                echo "                        <li class=\"nav-item";
                echo (((($context["page"] ?? null) == "Task")) ? (" active") : (""));
                echo "\"><a class=\"nav-link\" href=\"/task\">Задачи</a></li>
                        <li class=\"nav-item";
                // line 64
                echo (((($context["page"] ?? null) == "Settings")) ? (" active") : (""));
                echo "\"><a class=\"nav-link\" href=\"/settings\">Настройки</a></li>
                    ";
            }
            // line 66
            echo "                ";
        }
        // line 67
        echo "                <li class=\"nav-item";
        echo (((($context["page"] ?? null) == "About")) ? (" active") : (""));
        echo "\"><a class=\"nav-link\" href=\"/about\">О приложении</a></li>
                ";
        // line 68
        if ( !($context["user"] ?? null)) {
            // line 69
            echo "                <li class=\"nav-item";
            echo (((($context["page"] ?? null) == "Login")) ? (" active") : (""));
            echo "\"><a class=\"nav-link\" href=\"/login\">Войти</a></li>
                ";
        }
        // line 71
        echo "            </ul>
            <ul class=\"navbar-nav navbar-right\">
                ";
        // line 73
        if (($context["user"] ?? null)) {
            // line 74
            echo "                    <li class=\"nav-item\"><p>";
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["user"] ?? null), "login", array()), "html", null, true);
            echo "</p></li>
                    <li class=\"nav-item\"><a class=\"nav-link\" href=\"/logout\">Выйти</a></li>
                ";
        } else {
            // line 77
            echo "                    <li class=\"nav-item\" id=\"label\">Авторизируйтесь</li>
                ";
        }
        // line 79
        echo "            </ul>
        </div>
    </div>
</nav>
<!-- Подключаем конкретную страницу (экшн) -->
";
        // line 84
        $this->displayBlock('pageContent', $context, $blocks);
        // line 87
        echo "</body>
</html>";
    }

    // line 84
    public function block_pageContent($context, array $blocks = array())
    {
        // line 85
        echo "<div>No page content</div>
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
        return array (  213 => 85,  210 => 84,  205 => 87,  203 => 84,  196 => 79,  192 => 77,  185 => 74,  183 => 73,  179 => 71,  173 => 69,  171 => 68,  166 => 67,  163 => 66,  158 => 64,  153 => 63,  151 => 62,  146 => 61,  144 => 60,  140 => 59,  124 => 46,  120 => 45,  116 => 44,  95 => 25,  91 => 23,  80 => 21,  76 => 20,  72 => 18,  69 => 17,  65 => 15,  59 => 14,  51 => 12,  43 => 10,  40 => 9,  36 => 8,  32 => 6,  30 => 5,  24 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("<!DOCTYPE html>
<html>
<head>
    <meta charset=\"utf-8\">
    {% if def is not empty %}
    <!-- Defaults -->
    <script type=\"text/javascript\">
        {% for defItemKey in def|keys %}
            {% if def[defItemKey] is iterable %}
                {{defItemKey}} = JSON.parse('{{ def[defItemKey]|json_encode|raw }}');
            {% else %}
                {{defItemKey}} = \"{{ def[defItemKey] }}\";
            {% endif %}
        {% endfor %}
    </script>
    {% endif %}
    {% if frontendConstants is not empty %}
    <!-- Frontend constants -->
    <script type=\"text/javascript\">
        {% for frontendConstantsItemKey in frontendConstants|keys %}
        {{frontendConstantsItemKey}} = \"{{ frontendConstants[frontendConstantsItemKey] }}\";
        {% endfor %}
    </script>
    {% endif %}
    <!-- Latest compiled and minified CSS -->
    <link rel=\"stylesheet\" href=\"https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css\"
          integrity=\"sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB\" crossorigin=\"anonymous\">
    <!-- Bootstrap needs jQuery, so download it -->
    <script type=\"text/javascript\" src=\"https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js\"></script>
    <!--<script src=\"https://code.jquery.com/jquery-3.3.1.slim.min.js\"
             integrity=\"sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo\" crossorigin=\"anonymous\">
     </script>*}
     <script src=\"http://code.jquery.com/jquery-latest.min.js\"></script>-->
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
    <title>{{ title }}</title>
    <meta name=\"description\" content=\"{{ description }}\">
    <meta name=\"keywords\" content=\"{{ keywords }}\">
</head>
<body>
<!-- Меню -->
<nav class=\"navbar navbar-expand-lg navbar-dark bg-dark\">
    <div class=\"container\">
        <a class=\"navbar-brand\" href=\"/\">Собрание</a>
        <button class=\"navbar-toggler\" type=\"button\" data-toggle=\"collapse\" data-target=\"#navbar\" aria-controls=\"navbar\" aria-expanded=\"false\" aria-label=\"Toggle navigation\">
            <span class=\"navbar-toggler-icon\"></span>
        </button>

        <div class=\"collapse navbar-collapse\" id=\"navbar\" aria-expanded=\"false\" style=\"height: 1px;\" role=\"menu\">
            <ul class=\"navbar-nav mr-auto\">
                <li class=\"nav-item{{ page == 'Home' ? ' active' : '' }}\"><a class=\"nav-link\" href=\"/home\">Дом</a></li>
                {% if user %}
                    <li class=\"nav-item{{ page == 'User' ? ' active' : '' }}\"><a class=\"nav-link\" href=\"/user\">Пользователи</a></li>
                    {% if userType and userType.role != 'customer' %}
                        <li class=\"nav-item{{ page == 'Task' ? ' active' : '' }}\"><a class=\"nav-link\" href=\"/task\">Задачи</a></li>
                        <li class=\"nav-item{{ page == 'Settings' ? ' active' : '' }}\"><a class=\"nav-link\" href=\"/settings\">Настройки</a></li>
                    {% endif %}
                {% endif %}
                <li class=\"nav-item{{ page == 'About' ? ' active' : '' }}\"><a class=\"nav-link\" href=\"/about\">О приложении</a></li>
                {% if not user %}
                <li class=\"nav-item{{ page == 'Login' ? ' active' : '' }}\"><a class=\"nav-link\" href=\"/login\">Войти</a></li>
                {% endif %}
            </ul>
            <ul class=\"navbar-nav navbar-right\">
                {% if user %}
                    <li class=\"nav-item\"><p>{{ user.login }}</p></li>
                    <li class=\"nav-item\"><a class=\"nav-link\" href=\"/logout\">Выйти</a></li>
                {% else %}
                    <li class=\"nav-item\" id=\"label\">Авторизируйтесь</li>
                {% endif %}
            </ul>
        </div>
    </div>
</nav>
<!-- Подключаем конкретную страницу (экшн) -->
{% block pageContent %}
<div>No page content</div>
{% endblock %}
</body>
</html>", "base.tpl", "C:\\Program Files (x86)\\Ampps\\www\\roman.com\\application\\template\\View\\base.tpl");
    }
}

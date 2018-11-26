<?php

/* about.tpl */
class __TwigTemplate_83b71d991b034b87bba6ca5e339e5b2516f42264788340472f4a1d754f04c831 extends Twig_Template
{
    private $source;

    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        // line 1
        $this->parent = $this->loadTemplate("base.tpl", "about.tpl", 1);
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
    <h1>Приложение-помошник</h1>

    <h3>Помогает пользователям:</h3>
    <ul>
        <li>просмотреть свои предудущие задания и увидеть новые;</li>
    </ul>

    <h3>Помогает руководителям:</h3>
    <ul>
        <li>планировать новые задания с учетом особенностей пользователей;</li>
    </ul>

    <div class=\"text-justify\">
    <p>В разделе 'Пользователи' можно посмотреть пользователей собрания и их особенности а также изменять их.
        Аккаунты без доступа администратора могут видеть только себя.</p>

    <p>В разделе 'Задания' руководители могут создавать задания для пользователей для конкретной даты собрания, выбирать
    уроки и партнеров. Аккаунты без доступа администратора могут посмотреть только те собрания и задания, где они
    являются ответственными или партнерами</p>
    </div>

    <em>Приятного использования</em>
</div>
";
    }

    public function getTemplateName()
    {
        return "about.tpl";
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
<div class=\"container\">
    <h1>Приложение-помошник</h1>

    <h3>Помогает пользователям:</h3>
    <ul>
        <li>просмотреть свои предудущие задания и увидеть новые;</li>
    </ul>

    <h3>Помогает руководителям:</h3>
    <ul>
        <li>планировать новые задания с учетом особенностей пользователей;</li>
    </ul>

    <div class=\"text-justify\">
    <p>В разделе 'Пользователи' можно посмотреть пользователей собрания и их особенности а также изменять их.
        Аккаунты без доступа администратора могут видеть только себя.</p>

    <p>В разделе 'Задания' руководители могут создавать задания для пользователей для конкретной даты собрания, выбирать
    уроки и партнеров. Аккаунты без доступа администратора могут посмотреть только те собрания и задания, где они
    являются ответственными или партнерами</p>
    </div>

    <em>Приятного использования</em>
</div>
{% endblock %}", "about.tpl", "C:\\Program Files (x86)\\Ampps\\www\\roman.com\\application\\template\\View\\about.tpl");
    }
}

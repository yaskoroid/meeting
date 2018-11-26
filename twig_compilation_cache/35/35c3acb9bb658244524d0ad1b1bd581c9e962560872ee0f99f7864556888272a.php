<?php

/* about.tpl */
class __TwigTemplate_e20371c5fbff49c3cd9dcb18a0448b84897fe940e877b5028453fe93ef085e5a extends Twig_Template
{
    private $source;

    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        // line 1
        $this->parent = $this->loadTemplate("Base.tpl", "about.tpl", 1);
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
        return new Twig_Source("", "about.tpl", "C:\\Program Files (x86)\\Ampps\\www\\roman.com\\application\\template\\View\\about.tpl");
    }
}

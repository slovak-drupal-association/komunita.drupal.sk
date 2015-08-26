<?php

/* {# inline_template_start #}<span class="text module-description">{{ module_description }}</span> */
class __TwigTemplate_2722e563cad4a836ae03dda89606841ad986588289ba923931990de03ea524d5 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "<span class=\"text module-description\">";
        echo $this->env->getExtension('drupal_core')->escapeFilter($this->env, (isset($context["module_description"]) ? $context["module_description"] : null), "html", null, true);
        echo "</span>";
    }

    public function getTemplateName()
    {
        return "{# inline_template_start #}<span class=\"text module-description\">{{ module_description }}</span>";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  19 => 1,);
    }
}

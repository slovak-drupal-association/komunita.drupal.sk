<?php

/* {# inline_template_start #}<label id="{{ id }}" for="{{ enable_id }}" class="module-name table-filter-text-source">{{ module_name }}</label> */
class __TwigTemplate_de1939a2faa19c3d4119482b01165e896aabc3c0fd7e5bc6fd98370b04131d67 extends Twig_Template
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
        echo "<label id=\"";
        echo $this->env->getExtension('drupal_core')->escapeFilter($this->env, (isset($context["id"]) ? $context["id"] : null), "html", null, true);
        echo "\" for=\"";
        echo $this->env->getExtension('drupal_core')->escapeFilter($this->env, (isset($context["enable_id"]) ? $context["enable_id"] : null), "html", null, true);
        echo "\" class=\"module-name table-filter-text-source\">";
        echo $this->env->getExtension('drupal_core')->escapeFilter($this->env, (isset($context["module_name"]) ? $context["module_name"] : null), "html", null, true);
        echo "</label>";
    }

    public function getTemplateName()
    {
        return "{# inline_template_start #}<label id=\"{{ id }}\" for=\"{{ enable_id }}\" class=\"module-name table-filter-text-source\">{{ module_name }}</label>";
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

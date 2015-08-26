<?php

/* core/themes/classy/templates/dataset/item-list.html.twig */
class __TwigTemplate_d5e21a0e8fef9b233fb21112db38b17d212987e6418bcdd3319ba8d9710ceada extends Twig_Template
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
        // line 21
        if ($this->getAttribute((isset($context["context"]) ? $context["context"] : null), "list_style", array())) {
            // line 22
            echo "  ";
            $context["attributes"] = $this->getAttribute((isset($context["attributes"]) ? $context["attributes"] : null), "addClass", array(0 => ("item-list__" . $this->getAttribute((isset($context["context"]) ? $context["context"] : null), "list_style", array()))), "method");
        }
        // line 24
        if (((isset($context["items"]) ? $context["items"] : null) || (isset($context["empty"]) ? $context["empty"] : null))) {
            // line 25
            echo "<div class=\"item-list\">";
            // line 26
            if ( !twig_test_empty((isset($context["title"]) ? $context["title"] : null))) {
                // line 27
                echo "<h3>";
                echo $this->env->getExtension('drupal_core')->escapeFilter($this->env, (isset($context["title"]) ? $context["title"] : null), "html", null, true);
                echo "</h3>";
            }
            // line 29
            if ((isset($context["items"]) ? $context["items"] : null)) {
                // line 30
                echo "<";
                echo $this->env->getExtension('drupal_core')->escapeFilter($this->env, (isset($context["list_type"]) ? $context["list_type"] : null), "html", null, true);
                echo $this->env->getExtension('drupal_core')->escapeFilter($this->env, (isset($context["attributes"]) ? $context["attributes"] : null), "html", null, true);
                echo ">";
                // line 31
                $context['_parent'] = (array) $context;
                $context['_seq'] = twig_ensure_traversable((isset($context["items"]) ? $context["items"] : null));
                foreach ($context['_seq'] as $context["_key"] => $context["item"]) {
                    // line 32
                    echo "<li";
                    echo $this->env->getExtension('drupal_core')->escapeFilter($this->env, $this->getAttribute($context["item"], "attributes", array()), "html", null, true);
                    echo ">";
                    echo $this->env->getExtension('drupal_core')->escapeFilter($this->env, $this->getAttribute($context["item"], "value", array()), "html", null, true);
                    echo "</li>";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['item'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 34
                echo "</";
                echo $this->env->getExtension('drupal_core')->escapeFilter($this->env, (isset($context["list_type"]) ? $context["list_type"] : null), "html", null, true);
                echo ">";
            } else {
                // line 36
                echo $this->env->getExtension('drupal_core')->escapeFilter($this->env, (isset($context["empty"]) ? $context["empty"] : null), "html", null, true);
            }
            // line 38
            echo "</div>";
        }
    }

    public function getTemplateName()
    {
        return "core/themes/classy/templates/dataset/item-list.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  65 => 38,  62 => 36,  57 => 34,  47 => 32,  43 => 31,  38 => 30,  36 => 29,  31 => 27,  29 => 26,  27 => 25,  25 => 24,  21 => 22,  19 => 21,);
    }
}

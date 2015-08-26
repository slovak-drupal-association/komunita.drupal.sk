<?php

/* core/modules/system/templates/status-report.html.twig */
class __TwigTemplate_e905a6b427dd4c7891edbae270c543471aafd441ce6ee69b817e6b517f8473fa extends Twig_Template
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
        // line 20
        echo "<table class=\"system-status-report\">
  <thead>
    <tr class=\"visually-hidden\">
      <th>";
        // line 23
        echo $this->env->getExtension('drupal_core')->renderVar(t("Status"));
        echo "</th><th>";
        echo $this->env->getExtension('drupal_core')->renderVar(t("Component"));
        echo "</th><th>";
        echo $this->env->getExtension('drupal_core')->renderVar(t("Details"));
        echo "</th>
    </tr>
  </thead>
  <tbody>
  ";
        // line 27
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["requirements"]) ? $context["requirements"] : null));
        foreach ($context['_seq'] as $context["_key"] => $context["requirement"]) {
            // line 28
            echo "    ";
            if ($this->getAttribute($context["requirement"], "severity_status", array())) {
                // line 29
                echo "      <tr class=\"system-status-report__entry color-";
                echo $this->env->getExtension('drupal_core')->escapeFilter($this->env, $this->getAttribute($context["requirement"], "severity_status", array()), "html", null, true);
                echo "\">
    ";
            }
            // line 31
            echo "        <td class=\"system-status-report__status-icon system-status-report__status-icon--";
            echo $this->env->getExtension('drupal_core')->escapeFilter($this->env, $this->getAttribute($context["requirement"], "severity_status", array()), "html", null, true);
            echo "\">
          <div title=\"";
            // line 32
            echo $this->env->getExtension('drupal_core')->escapeFilter($this->env, $this->getAttribute($context["requirement"], "severity_title", array()), "html", null, true);
            echo "\">
            <span class=\"visually-hidden\">";
            // line 33
            echo $this->env->getExtension('drupal_core')->escapeFilter($this->env, $this->getAttribute($context["requirement"], "severity_title", array()), "html", null, true);
            echo "</span>
          </div>
        </td>
        <td class=\"system-status-report__status-title\">";
            // line 36
            echo $this->env->getExtension('drupal_core')->escapeFilter($this->env, $this->getAttribute($context["requirement"], "title", array()), "html", null, true);
            echo "</td>
        <td>
          ";
            // line 38
            echo $this->env->getExtension('drupal_core')->escapeFilter($this->env, $this->getAttribute($context["requirement"], "value", array()), "html", null, true);
            echo "
          ";
            // line 39
            if ($this->getAttribute($context["requirement"], "description", array())) {
                // line 40
                echo "            <div class=\"description\">";
                echo $this->env->getExtension('drupal_core')->escapeFilter($this->env, $this->getAttribute($context["requirement"], "description", array()), "html", null, true);
                echo "</div>
          ";
            }
            // line 42
            echo "        </td>
      </tr>
  ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['requirement'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 45
        echo "  </tbody>
</table>
";
    }

    public function getTemplateName()
    {
        return "core/modules/system/templates/status-report.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  88 => 45,  80 => 42,  74 => 40,  72 => 39,  68 => 38,  63 => 36,  57 => 33,  53 => 32,  48 => 31,  42 => 29,  39 => 28,  35 => 27,  24 => 23,  19 => 20,);
    }
}

<?php
/** 
 * @file
 * Contains \Drupal\texy\Plugin\Filter\FilterTexy.
 */

namespace Drupal\texy\Plugin\Filter;

use Drupal\filter\Plugin\FilterBase;

/** 
 * Provides a filter to display TEXY as HTMLt.
 * 
 * @Filter(
 *   id = "filter_texy",
 *   title = @Translation("Texy! filter"),
 *   type = Drupal\filter\Plugin\FilterInterface::TYPE_MARKUP_LANGUAGE,
 *   settings = {
 *   }
 *   module = "texy",
 *   weight = -10
 * )
 */
class FilterTexy extends FilterBase {

  /** 
   * {@inheritdoc}
   */
  public function process($text, $langcode) {
    $text = str_replace('foo', 'bar', $text);

    return $text;
  }


  /** 
   * {@inheritdoc}
   */
  public function tips($long = FALSE) {
    return $this->t('Texy!.');
  }

}

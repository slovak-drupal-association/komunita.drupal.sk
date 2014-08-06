<?php
/** 
 * @file
 * Contains \Drupal\texy\Plugin\Filter\FilterTexy.
 */

namespace Drupal\texy\Plugin\Filter;

use Drupal\filter\Plugin\FilterBase;

/** 
 * Provides a filter to display any HTML as plain text.
 *
 * @Filter(
 *   id = "filter_texy",
 *   module = "texy",
 *   title = @Translation("Texy! filter"),
 *   type = Drupal\filter\Plugin\FilterInterface::TYPE_MARKUP_LANGUAGE,
 *   weight = -10
 * )
 */
class FilterTexy extends FilterBase {

  /** 
   * {@inheritdoc}
   */
  public function process($text, $langcode, $cache, $cache_id) {
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

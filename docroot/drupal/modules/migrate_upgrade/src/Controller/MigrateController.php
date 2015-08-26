<?php
/**
 * @file
 * Contains Drupal\migrate_upgrade\Controller\MigrateController.
 */

namespace Drupal\migrate_upgrade\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Controller methods for the migration.
 */
class MigrateController extends ControllerBase {

  /**
   * Sets a log filter and redirects to the log.
   */
  public function showLog() {
    $_SESSION['dblog_overview_filter'] = array();
    $_SESSION['dblog_overview_filter']['type'] = array('migrate_upgrade' => 'migrate_upgrade');
    return $this->redirect('dblog.overview');
  }
}

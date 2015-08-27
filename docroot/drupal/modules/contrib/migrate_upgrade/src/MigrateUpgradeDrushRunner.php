<?php

/**
 * @file
 * Contains \Drupal\migrate_upgrade\MigrateUpgradeDrushRunner.
 */

namespace Drupal\migrate_upgrade;
use Drupal\migrate\MigrateExecutable;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\migrate_tools\DrushLogMigrateMessage;

class MigrateUpgradeDrushRunner {

  use MigrateUpgradeTrait;
  use StringTranslationTrait;

  /**
   * The list of migrations to run and their configuration.
   *
   * @var array
   */
  protected $migrationList;

  /**
   * From the provided source information, instantiate the appropriate migrations
   * in the active configuration.
   *
   * @throws \Exception
   */
  public function configure() {
    $db_url = drush_get_option('legacy-db-url');
    $db_spec = drush_convert_db_from_db_url($db_url);
    $db_prefix = drush_get_option('legacy-db-prefix');
    $db_spec['prefix'] = $db_prefix;

    $this->migrationList = $this->configureMigrations($db_spec, drush_get_option('legacy-root'));
  }

  /**
   * Run the configured migrations.
   */
  public function import() {
    $log = new DrushLogMigrateMessage();
    foreach ($this->migrationList as $migration_id) {
      $migration = entity_load('migration', $migration_id);
      drush_print(dt('Importing !migration', array('!migration' => $migration_id)));
      $executable = new MigrateExecutable($migration, $log);
      // drush_op() provides --simulate support.
      drush_op(array($executable, 'import'));
    }
  }

}

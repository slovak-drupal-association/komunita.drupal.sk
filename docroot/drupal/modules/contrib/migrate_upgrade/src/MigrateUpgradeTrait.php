<?php

/**
 * @file
 * Contains \Drupal\migrate_upgrade\MigrateUpgradeTrait.
 */

namespace Drupal\migrate_upgrade;

use Drupal\Core\Database\Connection;
use Drupal\Core\Database\Database;
use Drupal\migrate\Entity\Migration;
use Drupal\migrate\Exception\RequirementsException;
use Drupal\migrate\MigrationBuilder;
use Drupal\migrate\Plugin\RequirementsInterface;
use Drupal\Component\Plugin\Exception\PluginNotFoundException;

/**
 * Trait providing functionality to instantiate the appropriate migrations for
 * a given source Drupal database. Note the class using the trait must
 * implement TranslationInterface (i.e., define t()).
 */
trait MigrateUpgradeTrait {

  /**
   * Set up the relevant migrations for the provided database connection.
   *
   * @param \Drupal\Core\Database\Database $database
   *   Database array representing the source Drupal database.
   * @param string $site_address
   *   Address of the source Drupal site (e.g., http://example.com/).
   *
   * @return array
   */
  protected function configureMigrations(array $database, $site_address) {
    // Set up the connection.
    Database::addConnectionInfo('upgrade', 'default', $database);
    $connection = Database::getConnection('default', 'upgrade');

    if (!$drupal_version = $this->getLegacyDrupalVersion($connection)) {
      throw new \Exception($this->t('Source database does not contain a recognizable Drupal version.'));
    }

    $group_name = 'Drupal ' . $drupal_version;

    $template_storage = \Drupal::service('migrate.template_storage');
    $migration_templates = $template_storage->findTemplatesByTag($group_name);
    foreach ($migration_templates as $id => $template) {
      // Configure file migrations so they can find the files.
      if ($template['destination']['plugin'] == 'entity:file') {
        if ($site_address) {
          // Make sure we have a single trailing slash.
          $site_address = rtrim($site_address, '/') . '/';
          $migration_templates[$id]['destination']['source_base_path'] = $site_address;
        }
      }
      // @todo: Use a group to hold the db info, so we don't have to stuff it
      // into every migration.
      $migration_templates[$id]['source']['key'] = 'upgrade';
      $migration_templates[$id]['source']['database'] = $database;
    }

    /** @var \Drupal\migrate\MigrationBuilder $builder */
    $builder = \Drupal::service('migrate.migration_builder');
    $migrations = $builder->createMigrations($migration_templates, FALSE);

    $migration_ids = [];
    foreach ($migrations as $migration) {
      try {
        if ($migration->getSourcePlugin() instanceof RequirementsInterface) {
          $migration->getSourcePlugin()->checkRequirements();
        }
        if ($migration->getDestinationPlugin() instanceof RequirementsInterface) {
          $migration->getDestinationPlugin()->checkRequirements();
        }
        $migration->save();
        $migration_ids[] = $migration->id();
      }
      // Migrations which are not applicable given the source and destination
      // site configurations (e.g., what modules are enabled) will be silently
      // ignored.
      catch (RequirementsException $e) {
      }
      catch (PluginNotFoundException $e) {
      }
    }

    // We need the migration ids in order because they're passed directly to the
    // batch runner which loads one migration at a time.
    return array_keys(entity_load_multiple('migration', $migration_ids));
  }

  /**
   * Determine what version of Drupal the source database contains, based on
   * what tables are present.
   *
   * @param \Drupal\Core\Database\Connection $connection
   *
   * @return int|null
   */
  protected function getLegacyDrupalVersion(Connection $connection) {
    $version_string = FALSE;

    // @todo: Don't assume because a table of that name exists, that it has
    // the columns we're querying. Catch exceptions and report that the source
    // database is not Drupal.

    // Detect Drupal 5/6/7.
    if ($connection->schema()->tableExists('system')) {
      $version_string = $connection->query('SELECT schema_version FROM {system} WHERE name = :module', [':module' => 'system'])->fetchField();
      if ($version_string && $version_string[0] == '1') {
        // @todo: This misidentifies 4.x as 5.
        $version_string = '5';
      }
    }
    // Detect Drupal 8.
    elseif ($connection->schema()->tableExists('key_value')) {
      $result = $connection->query("SELECT value FROM {key_value} WHERE collection = :system_schema  and name = :module", [':system_schema' => 'system.schema', ':module' => 'system'])->fetchField();
      $version_string = unserialize($result);
    }

    // @TODO I wonder if a hook here would help contrib support other version?

    return $version_string ? substr($version_string, 0, 1) : FALSE;
  }

}

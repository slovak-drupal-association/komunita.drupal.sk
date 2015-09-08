<?php

/**
 * @file
 * Contains \Drupal\migrate_upgrade\MigrateUpgradeRunBatch.
 */

namespace Drupal\migrate_upgrade;

use Drupal\migrate\Entity\MigrationInterface;
use Drupal\migrate\MigrateExecutable;
use Drupal\Core\Url;

class MigrateUpgradeRunBatch {

  /**
   * @param $initial_ids
   *   The initial migration IDs.
   * @param $context
   *   The batch context.
   */
  public static function run($initial_ids, &$context) {
    if (!isset($context['sandbox']['migration_ids'])) {
      $context['sandbox']['max'] = count($initial_ids);
      $context['sandbox']['migration_ids'] = $initial_ids;
      $context['results']['failures'] = 0;
      $context['results']['successes'] = 0;
    }

    $migration_id = reset($context['sandbox']['migration_ids']);
    $migration = entity_load('migration', $migration_id);
    if ($migration) {
      $messages = new MigrateMessageCapture();
      $executable = new MigrateExecutable($migration, $messages);

      // @TODO, if label isn't required we could implement this logic on the
      // migration entity itself.
      $migration_name = $migration->label() ? $migration->label() : $migration_id;
      static::logger()->notice('Importing @migration', array('@migration' => $migration_name));

      try  {
        $migration_status = $executable->import();
      }
      catch (\Exception $e) {
        // PluginNotFoundException is when the D8 module is disabled, maybe that
        // should be a RequirementsException instead.
        static::logger()->error($e->getMessage());
        $migration_status = MigrationInterface::RESULT_FAILED;
      }

      switch ($migration_status) {
        case MigrationInterface::RESULT_COMPLETED:
          $context['message'] = t('Imported @migration', array('@migration' => $migration_name));
          $context['results']['successes']++;
          static::logger()->notice('Imported @migration', array('@migration' => $migration_name));
          break;

        case MigrationInterface::RESULT_INCOMPLETE:
          $context['message'] = t('Importing @migration', array('@migration' => $migration_name));
          break;

        case MigrationInterface::RESULT_STOPPED:
          $context['message'] = t('Import stopped by request');
          break;

        case MigrationInterface::RESULT_FAILED:
          $context['message'] = t('Import of @migration failed', array('@migration' => $migration_name));
          $context['results']['failures']++;
          static::logger()->error('Import of @migration failed', array('@migration' => $migration_name));
          break;

        case MigrationInterface::RESULT_SKIPPED:
          $context['message'] = t('Import of @migration skipped due to unfulfilled dependencies', array('@migration' => $migration_name));
          static::logger()->error('Import of @migration skipped due to unfulfilled dependencies', array('@migration' => $migration_name));
          break;

        case MigrationInterface::RESULT_DISABLED:
          // Skip silently if disabled.
          break;
      }

      // Add any captured messages.
      foreach ($messages->getMessages() as $message) {
        $context['message'] .= "<br />\n" . $message;
      }

      // Unless we're continuing on with this migration, take it off the list.
      if ($migration_status != MigrationInterface::RESULT_INCOMPLETE) {
        array_shift($context['sandbox']['migration_ids']);
      }
    }
    else {
      array_shift($context['sandbox']['migration_ids']);
    }

    $context['finished'] = 1 - count($context['sandbox']['migration_ids']) / $context['sandbox']['max'];
  }

  /**
   * A helper method to grab the logger using the migrate_upgrade channel.
   *
   * @return \Psr\Log\LoggerInterface
   *   The logger instance.
   */
  protected static function logger() {
    return \Drupal::logger('migrate_upgrade');
  }

  /**
   * Implementation of the Batch API finished method.
   */
  public static function finished($success, $results, $operations, $elapsed) {
    static::displayResults($results);
  }

  /**
   * Display counts of success/failures on the migration upgrade complete page.
   *
   * @param $results
   *   An array of result data built during the batch.
   */
  protected static function displayResults($results) {
    $successes = $results['successes'];
    $failures = $results['failures'];
    $translation = \Drupal::translation();

    // If we had any successes lot that for the user.
    if ($successes > 0) {
      drupal_set_message(t('Import completed @count successfully.', array('@count' => $translation->formatPlural($successes, '1 migration', '@count migrations'))));
    }

    // If we had failures, log them and show the migration failed.
    if ($failures > 0) {
      drupal_set_message(t('@count failed', array('@count' => $translation->formatPlural($failures, '1 migration', '@count migrations'))), 'error');
      drupal_set_message(t('Import process not completed'), 'error');
    }
    else {
      // Everything went off without a hitch. We may not have had successes but
      // we didn't have failures so this is fine.
      drupal_set_message(t('Congratulations, you upgraded Drupal!'));
    }

    if (\Drupal::moduleHandler()->moduleExists('dblog')) {
      $url = Url::fromRoute('migrate_upgrade.log');
      drupal_set_message(\Drupal::l(t('Review the detailed migration log'), $url), $failures ? 'error' : 'status');
    }
  }

}

<?php

/**
 * @file
 * Contains \Drupal\migrate_upgrade\Form\MigrateUpgradeForm.
 */

namespace Drupal\migrate_upgrade\Form;

use Drupal\Core\Installer\Form\SiteSettingsForm;
use Drupal\Core\Form\FormStateInterface;
use Drupal\migrate_upgrade\MigrateUpgradeTrait;

class MigrateUpgradeForm extends SiteSettingsForm {

  use MigrateUpgradeTrait;

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'migrate_upgrade_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    // Make sure the install API is available.
    include_once DRUPAL_ROOT . '/core/includes/install.inc';

    $form = parent::buildForm($form, $form_state);
    $form['#title'] = $this->t('Drupal Upgrade: Source site information');

    $form['source'] = array(
      '#type' => 'details',
      '#title' => $this->t('Source site'),
      '#open' => TRUE,
      '#weight' => 0,
    );
    $form['source']['site_address'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Source site address'),
      '#default_value' => 'http://',
      '#description' => $this->t('Enter the address of your current Drupal ' .
        'site (e.g. "http://www.example.com"). This address will be used to ' .
        'retrieve any public files from the site.'),
    );
    $form['files'] = array(
      '#type' => 'details',
      '#title' => $this->t('Files'),
      '#open' => TRUE,
      '#weight' => 2,
    );
    $form['files']['private_file_directory'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Private file directory'),
      '#description' => $this->t('If you have private files on your current ' .
        'Drupal site which you want imported, please copy the complete private ' .
        'file directory to a place accessible by your new Drupal 8 web server. ' .
        'Enter the address of the directory (e.g., "/home/legacy_files/private" ' .
        'or "http://private.example.com/legacy_files/private") here.'),
    );
    $form['database'] = array(
      '#type' => 'details',
      '#title' => $this->t('Source database'),
      '#description' => $this->t('Provide credentials for the database of the Drupal site you want to migrate.'),
      '#open' => TRUE,
      '#weight' => 1,
    );

    // Copy the values from the parent form into our structure.
    $form['database']['driver'] = $form['driver'];
    $form['database']['settings'] = $form['settings'];
    $form['database']['settings']['mysql']['host'] = $form['database']['settings']['mysql']['advanced_options']['host'];
    $form['database']['settings']['mysql']['host']['#title'] = 'Database host';
    $form['database']['settings']['mysql']['host']['#weight'] = 0;

    // Remove the values from the parent form.
    unset($form['driver']);
    unset($form['database']['settings']['mysql']['database']['#default_value']);
    unset($form['settings']);
    unset($form['database']['settings']['mysql']['advanced_options']['host']);

    // Rename the submit button.
    $form['actions']['save']['#value'] = $this->t('Perform upgrade');

    // The parent form uses #limit_validation_errors to avoid validating the
    // unselected database drivers. This makes it difficult for us to handle
    // database errors in our validation, and does not appear to actually be
    // necessary with the current implementation, so we remove it.
    unset($form['actions']['save']['#limit_validation_errors']);

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    // Retrieve the database driver from the form, use reflection to get the
    // namespace and then construct a valid database array the same as in
    // settings.php.
    $driver = $form_state->getValue('driver');
    $drivers = $this->getDatabaseTypes();
    $reflection = new \ReflectionClass($drivers[$driver]);
    $install_namespace = $reflection->getNamespaceName();

    $database = $form_state->getValue($driver);
    // Cut the trailing \Install from namespace.
    $database['namespace'] = substr($install_namespace, 0, strrpos($install_namespace, '\\'));
    $database['driver'] = $driver;

    // Validate the driver settings and just end here if we have any issues.
    if ($errors = $drivers[$driver]->validateDatabaseSettings($database)) {
      foreach ($errors as $name => $message) {
        $form_state->setErrorByName($name, $message);
      }
      return;
    }

    try {
      // Set up all the relevant migrations and get their IDs so we can run them.
      $migration_ids = $this->configureMigrations($database, $form_state->getValue('site_address'));

      // Store the retrieved migration ids on the form state.
      $form_state->setValue('migration_ids', $migration_ids);
    }
    catch (\Exception $e) {
      $form_state->setErrorByName(NULL, $this->t($e->getMessage()));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $batch = array(
      'title' => $this->t('Running migrations'),
      'progress_message' => '',
      'operations' => array(
        array(array('Drupal\migrate_upgrade\MigrateUpgradeRunBatch', 'run'), array($form_state->getValue('migration_ids'))),
      ),
      'finished' => array('Drupal\migrate_upgrade\MigrateUpgradeRunBatch', 'finished'),
    );
    batch_set($batch);
    $form_state->setRedirect('<front>');
  }

  /**
   * Returns all supported database driver installer objects.
   *
   * @return \Drupal\Core\Database\Install\Tasks[]
   *   An array of available database driver installer objects.
   */
  protected function getDatabaseTypes() {
    // Make sure the install API is available.
    include_once DRUPAL_ROOT . '/core/includes/install.core.inc';
    return drupal_get_database_types();
  }

}

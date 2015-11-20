<?php
/**
 * @file
 * Enables modules and site configuration for a standard site installation.
 */

use Drupal\contact\Entity\ContactForm;
use Drupal\Core\Config\ConfigImporter;
use Drupal\Core\Config\StorageComparer;
use Drupal\Core\Form\FormStateInterface;

/**
 * Implements hook_form_FORM_ID_alter() for install_configure_form().
 *
 * Allows the profile to alter the site configuration form.
 */
function drupalsk_form_install_configure_form_alter(&$form, FormStateInterface $form_state) {
  // Default clean_url to ON.
  $form['server_settings']['clean_url']['#default_value'] = 1;

  // Set a default country.
  $form['regional_settings']['site_default_country']['#default_value'] = 'SK';

  // Add a placeholder as example that one can choose an arbitrary site name.
  $form['site_information']['site_name']['#value'] = t('Drupal Slovensko');
  $form['site_information']['site_mail']['#value'] = 'info@drupal.sk';

  // Set the UID:1 user name and hide away the input.
  $form['admin_account']['account']['name']['#default_value'] = 'admin';
  $form['admin_account']['account']['name']['#type'] = 'hidden';
  $form['admin_account']['account']['mail']['#default_value'] = 'info@drupal.sk';

  // We don't admin's password to be set at all - for security reasons.
  // $form['admin_account']['account']['pass']['#value'] = 'admin';
  $form['admin_account']['account']['pass']['#type'] = 'hidden';
  $form['admin_account']['account']['pass']['#required'] = FALSE;

  // Timezone handling.
  $form['regional_settings']['date_default_timezone']['#default_value'] = "Europe/Bratislava";
  $form['regional_settings']['date_default_timezone']['#attributes'] = NULL;

  $form['#submit'][] = 'standard_form_install_configure_submit';
}

/**
 * Submission handler to sync the contact.form.feedback recipient.
 */
function drupalsk_form_install_configure_submit($form, FormStateInterface $form_state) {
  $site_mail = $form_state->getValue('site_mail');
  ContactForm::load('feedback')
    ->setRecipients([$site_mail])
    ->trustData()
    ->save();
}

/**
 * Set the UUID of this website.
 *
 * By default, reinstalling a site will assign it a new random UUID, making
 * it impossible to sync configuration with other instances. This function
 * is called by site deployment module's .install hook.
 *
 * @param $uuid
 *   A uuid string, for example 'e732b460-add4-47a7-8c00-e4dedbb42900'.
 */
function drupalsk_set_uuid($uuid) {
  \Drupal::configFactory()->getEditable('system.site')
    ->set('uuid', $uuid)
    ->save();
}

/**
 * Imports languages via a batch process during installation.
 *
 * @param $install_state
 *   An array of information about the current installation state.
 *
 * @return
 *   The batch definition, if there are language files to import.
 */
function drupalsk_import_translations(&$install_state) {
  \Drupal::moduleHandler()->loadInclude('locale', 'translation.inc');
  \Drupal::moduleHandler()->loadInclude('install', 'core.inc');

  // If there is more than one language or the single one is not English, we
  // should import translations.
  $operations = install_download_additional_translations_operations($install_state);
//  $languages = \Drupal::languageManager()->getLanguages();
//  if (count($languages) > 1 || !isset($languages['en'])) {
  $operations[] = array(
    '_install_prepare_import',
    array(array('sk'), $install_state['server_pattern'])
  );

  $projects = array_keys(locale_translation_get_projects());

  // Set up a batch to import translations for drupal core. Translation import
  // for contrib modules happens in install_import_translations_remaining.
//    foreach ($languages as $language) {
  foreach ($projects as $project) {
    if (locale_translation_use_remote_source()) {
      $operations[] = array(
        'locale_translation_batch_fetch_download',
//            array($project, $language->getId())
        array($project, 'sk')
      );
    }
    $operations[] = array(
      'locale_translation_batch_fetch_import',
      array($project, 'sk', array())
//          array($project, $language->getId(), array())
    );
  }
//    }

  module_load_include('fetch.inc', 'locale');
  $batch = array(
    'operations' => $operations,
    'title' => t('Updating translations.'),
    'progress_message' => '',
    'error_message' => t('Error importing translation files'),
    'finished' => 'locale_translation_batch_fetch_finished',
    'file' => drupal_get_path('module', 'locale') . '/locale.batch.inc',
  );
  return $batch;
//  }
}

function drupalsk_import_config() {
  // Compare changes in configuration.
  $storage_comparer = new StorageComparer(
    \Drupal::getContainer()->get('config.storage.sync'),
    \Drupal::getContainer()->get('config.storage'),
    \Drupal::getContainer()->get('config.manager')
  );

  // Prepare importer.
  $configImporter = new ConfigImporter(
    $storage_comparer->createChangelist(),
    \Drupal::getContainer()->get('event_dispatcher'),
    \Drupal::getContainer()->get('config.manager'),
    \Drupal::getContainer()->get('lock'),
    \Drupal::getContainer()->get('config.typed'),
    \Drupal::getContainer()->get('module_handler'),
    \Drupal::getContainer()->get('module_installer'),
    \Drupal::getContainer()->get('theme_handler'),
    \Drupal::getContainer()->get('string_translation')
  );

  // Import configuration.
  $configImporter->import();
}
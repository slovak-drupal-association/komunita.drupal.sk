<?php
/**
 * @file
 * Enables modules and site configuration for a standard site installation.
 */

use Drupal\contact\Entity\ContactForm;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Extension\InfoParser;

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
  ContactForm::load('feedback')->setRecipients([$site_mail])->trustData()->save();
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
  \Drupal::configFactory() ->getEditable('system.site')
  ->set('uuid', $uuid)
    ->save();
}

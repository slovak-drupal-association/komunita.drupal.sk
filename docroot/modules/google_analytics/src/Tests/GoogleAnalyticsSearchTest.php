<?php

/**
 * @file
 * Contains \Drupal\google_analytics\Tests\GoogleAnalyticsBasicTest.
 */

namespace Drupal\google_analytics\Tests;

use Drupal\simpletest\WebTestBase;

class GoogleAnalyticsSearchTest extends WebTestBase {

  /**
   * Modules to enable.
   *
   * @var array
   */
  public static $modules = array('google_analytics', 'search', 'node');

  public static function getInfo() {
    return array(
      'name' => 'Google Analytics search tests',
      'description' => 'Test search functionality of Google Analytics module.',
      'group' => 'Google Analytics',
    );
  }

  function setUp() {
    parent::setUp();

    $permissions = array(
      'access administration pages',
      'administer google analytics',
      'search content',
    );

    // User to set up google_analytics.
    $this->admin_user = $this->drupalCreateUser($permissions);
    $this->drupalLogin($this->admin_user);
  }

  function testGoogleAnalyticsSearchTracking() {
    $ua_code = 'UA-123456-1';
    \Drupal::config('google_analytics.settings')->set('account', $ua_code)->save();

    // Check tracking code visibility.
    $this->drupalGet('');
    $this->assertRaw($ua_code, '[testGoogleAnalyticsSearch]: Tracking code is displayed for authenticated users.');

    $this->drupalGet('search/node');
    $this->assertNoRaw('ga("set", "page",', '[testGoogleAnalyticsSearch]: Custom url not set.');

    // Search for random string.
    $edit = array();
    $edit['keys'] = $this->randomName(32);

    \Drupal::config('google_analytics.settings')->set('track.site_search', 1)->save();
    $this->drupalPostForm('search/node', $edit, t('Search'));
    $this->assertRaw('ga("set", "page", (window.google_analytics_search_results) ?', '[testGoogleAnalyticsSearch]: Search results tracker is displayed.');

    // Test search results counter.
    $this->assertRaw('window.google_analytics_search_results = ', '[testGoogleAnalyticsSearch]: Search results counter is displayed.');

  }
}

# Drupal core
UPDATE authmap SET authname = CONCAT('http://aid', aid, '.uid', uid, '.drupal.sk'), module = 'drupal' WHERE aid != '1';
UPDATE users SET name=CONCAT('user', uid), pass='heslo', init=CONCAT('user', uid, '@example.com') WHERE uid != 0;
UPDATE users SET mail=CONCAT('user', uid, '@example.com') WHERE uid != 0;
UPDATE comments SET name='Anonymous', mail='', homepage='http://example.com', hostname='1.1.1.1' WHERE uid=0;
UPDATE contact SET recipients = 'drupalsk@localhost';
UPDATE history SET timestamp = '280281600';
DELETE FROM watchdog;
DELETE FROM sessions;
TRUNCATE TABLE cache;
TRUNCATE TABLE cache_block;
TRUNCATE TABLE cache_filter;
TRUNCATE TABLE cache_form;
TRUNCATE TABLE cache_page;
TRUNCATE TABLE cache_menu;
TRUNCATE TABLE cache_update;
TRUNCATE TABLE openid_association;

# User data
UPDATE profile_values SET value = 'Real Name' WHERE fid = '1';
UPDATE profile_values SET value = 'http://drupal.sk' WHERE fid = '2';
UPDATE profile_values SET value = 'SDA' WHERE fid = '5';
UPDATE profile_values SET value = 1 WHERE fid = '9';
UPDATE profile_values SET value = '1970-01-01 00:00:01' WHERE fid = '10';
UPDATE profile_values SET value = '@drupal_sk' WHERE fid = '13';

# Varibles
DELETE FROM variable
WHERE (name = 'acquia_agent_cloud_migration' OR
name = 'acquia_agent_verify_peer' OR
name = 'acquia_identifier' OR
name = 'acquia_key' OR
name = 'acquia_migrate_files' OR
name = 'acquia_spi_boot_last' OR
name = 'acquia_spi_cron_last' OR
name = 'acquia_spi_module_rebuild' OR
name = 'acquia_subscription_data' OR
name = 'acquia_subscription_name');
UPDATE variable SET value = 's:32:"aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa";' WHERE name = 'boost_crawler_key';
UPDATE variable SET value = 's:64:"aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa";' WHERE name = 'drupal_private_key';
UPDATE variable SET value = 's:33:"aaaaaaaaaaaaaaaaaaaaa:aaaaaaaaaaa";' WHERE name = 'google_cse_cx';
UPDATE variable SET value = 's:86:"aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa";' WHERE name = 'googlemap_api_key';
UPDATE variable SET value = '' WHERE name = 'shield_user';
UPDATE variable SET value = '' WHERE name = 'shield_pass';

# Captcha
TRUNCATE TABLE captcha_sessions;

# CCK (Content)
TRUNCATE TABLE cache_content;

# Devel
TRUNCATE TABLE devel_queries;
TRUNCATE TABLE devel_times;

# Location
UPDATE location SET city = 'Schneekoppe', latitude = '50.735942477452205', longitude = '15.739728212356567';
TRUNCATE TABLE cache_location;

# Path
DELETE FROM url_alias WHERE src LIKE 'user/%';

# Views
TRUNCATE TABLE cache_views;
TRUNCATE TABLE cache_views_data;
TRUNCATE TABLE views_object_cache;

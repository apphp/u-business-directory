
DELETE FROM `<DB_PREFIX>modules` WHERE `code` = 'directory';

DELETE FROM `<DB_PREFIX>module_settings` WHERE `module_code` = 'directory';
DELETE FROM `<DB_PREFIX>module_settings` WHERE `module_code` = 'directory:info';

DELETE FROM `<DB_PREFIX>role_privileges` WHERE `privilege_id` IN (SELECT id FROM `<DB_PREFIX>privileges` WHERE `category` = 'directory');

DELETE FROM `<DB_PREFIX>rss_channels` WHERE `channel_code` = 'directory';

DELETE FROM `<DB_PREFIX>privileges` WHERE `category` = 'directory';
DELETE FROM `<DB_PREFIX>backend_menu_translations` WHERE `menu_id` IN (SELECT id FROM `<DB_PREFIX>backend_menus` WHERE `module_code` = 'directory');
DELETE FROM `<DB_PREFIX>backend_menus` WHERE `module_code` = 'directory';

DELETE FROM `<DB_PREFIX>frontend_menu_translations` WHERE `menu_id` IN (SELECT id FROM `<DB_PREFIX>frontend_menus` WHERE `module_code` = 'directory');
DELETE FROM `<DB_PREFIX>frontend_menus` WHERE `module_code` = 'directory';

DELETE FROM `<DB_PREFIX>search_categories` WHERE `module_code` = 'directory';

DELETE FROM `<DB_PREFIX>accounts` WHERE `id` IN (SELECT account_id FROM `<DB_PREFIX>customers`);
DELETE FROM `<DB_PREFIX>email_template_translations` WHERE `template_code` IN (SELECT code FROM `<DB_PREFIX>email_templates` WHERE `module_code` = 'directory');
DELETE FROM `<DB_PREFIX>email_templates` WHERE `module_code` = 'directory';

UPDATE `<DB_PREFIX>site_info` SET `header` = 'PHP Directy CMF', `slogan` = 'Welcome to PHP Directy CMF!', `footer` = 'PHP Directy CMF © <a class="footer_link" target="_new" href="http://www.apphp.com/php-directy-cmf/index.php">ApPHP</a>', `meta_title` = 'PHP Directy CMF', `meta_description` = 'Directy CMF', `meta_keywords` = 'php cmf, php framework, php content management framework, php cms';

DROP TABLE IF EXISTS `<DB_PREFIX>customers`;
DROP TABLE IF EXISTS `<DB_PREFIX>customer_groups`;
DROP TABLE IF EXISTS `<DB_PREFIX>regions`;
DROP TABLE IF EXISTS `<DB_PREFIX>region_translations`;
DROP TABLE IF EXISTS `<DB_PREFIX>categories`;
DROP TABLE IF EXISTS `<DB_PREFIX>category_translations`;
DROP TABLE IF EXISTS `<DB_PREFIX>listings`;
DROP TABLE IF EXISTS `<DB_PREFIX>listings_categories`;
DROP TABLE IF EXISTS `<DB_PREFIX>listing_translations`;
DROP TABLE IF EXISTS `<DB_PREFIX>advertise_plans`;
DROP TABLE IF EXISTS `<DB_PREFIX>advertise_plan_translations`;
DROP TABLE IF EXISTS `<DB_PREFIX>orders`;
DROP TABLE IF EXISTS `<DB_PREFIX>inquiries`;
DROP TABLE IF EXISTS `<DB_PREFIX>inquiries_history`;
DROP TABLE IF EXISTS `<DB_PREFIX>inquiries_replies`;
DROP TABLE IF EXISTS `<DB_PREFIX>reviews`;
DROP TABLE IF EXISTS `<DB_PREFIX>site_info_frontend`;
DROP TABLE IF EXISTS `<DB_PREFIX>social_networks`;
DROP TABLE IF EXISTS `<DB_PREFIX>social_networks_login`;

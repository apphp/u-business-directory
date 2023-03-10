INSERT INTO `<DB_PREFIX>modules` (`id`, `code`, `name`, `description`, `version`, `icon`, `show_on_dashboard`, `show_in_menu`, `is_installed`, `is_system`, `is_active`, `sort_order`) VALUES
(NULL, 'directory', 'Business Directory', 'This module allows to create web-directory and inquiry based business listings site', '0.0.1', 'icon.png', 1, 0, 1, 1, 1, (SELECT (MAX(m.sort_order)+1) FROM `<DB_PREFIX>modules` m));

INSERT INTO `<DB_PREFIX>module_settings` (`id`, `module_code`, `property_group`, `property_key`, `property_value`, `name`, `description`, `property_type`, `property_source`, `is_required`, `property_length`)VALUES
(NULL, 'directory', '', 'change_customer_password', '1', 'Admin Changes Password', 'Specifies whether to allow changing customer password by Admin', 'bool', '', 0, ''),
(NULL, 'directory', '', 'customer_approval_type', 'automatic', 'Approval Type', 'Specifies which type of approval is required for customer registration', 'enum', 'by_admin,by_email,automatic', 0, ''),
(NULL, 'directory', '', 'customer_removal_type', 'physical', 'Remove Account Type', 'Specifies the type of customer account removal: logical, physical or both', 'enum', 'logical,physical,physical_or_logical',0, ''),
(NULL, 'directory', ' Listing Fields', 'listing_approval', 'by_admin', 'Listings Approval', 'Specifies whether to approve listing automatically or by admin', 'enum', 'automatic,by_admin', 0, ''),
(NULL, 'directory', ' Listing Fields', 'featured_listings_count', '4', 'Featured Listings Count', 'Defines how many featured listings headlines will be shown in the featured listings block', 'range', '1-10', 0, ''),
(NULL, 'directory', ' Listing Fields', 'moduleblock', 'drawFeaturedBlock', 'Featured Listings Block', 'Draws Featured Listings side block', 'label', '', 0, ''),
(NULL, 'directory', ' Listing Fields', 'moduleblock', 'drawRecentListingsBlock', 'Recent Listings Block', 'Draws Recent Listings side block', 'label', '', 0, ''),
(NULL, 'directory', ' Listing Fields', 'latest_listings', '10', 'Latest published listings', 'Number of listings displayed in the block Latest published listings', 'enum', 'not_show,4,5,6,7,8,9,10,12,15,20,25', 0, ''),
(NULL, 'directory', 'Account Login', 'customer_allow_login', '1', 'Allow Customers to Login', 'Specifies whether to allow existing customers to login', 'bool', '', 0, ''),
(NULL, 'directory', 'Account Login', 'customer_allow_remember_me', '1', 'Allow Remember Me', 'Specifies whether to allow Remember Me feature', 'bool', '', 0, ''),
(NULL, 'directory', 'Account Login', 'modulelink', 'customers/login', 'Customer Login Link', 'This link leads to the page where customer can login to the site', 'label', '', 0, ''),
(NULL, 'directory', 'Account Login', 'moduleblock', 'drawLoginBlock', 'Login Block', 'Draws login side block', 'label', '', 0, ''),
(NULL, 'directory', 'Account Registration', 'customer_allow_registration', '1', 'Allow Customers to Register', 'Specifies whether to allow new customers to register', 'bool', '', 0, ''),
(NULL, 'directory', 'Account Registration', 'customer_new_registration_alert', '1', 'New Registration Admin Alert', 'Specifies whether to alert admin on new customer registration', 'bool', '', 0, ''),
(NULL, 'directory', 'Account Registration', 'modulelink', 'customers/registration', 'Customer Registration Link', 'This link leads to the page where customer can register to the site', 'label', '', 0, ''),
(NULL, 'directory', 'Account Restore Password', 'customer_allow_restore_password', '1', 'Allow Restore Password', 'Specifies whether to allow customers to restore their passwords', 'bool', '', 0, ''),
(NULL, 'directory', 'Account Restore Password', 'modulelink', 'customers/restorePassword', 'Restore Password Link', 'This link leads to the page where customer may restore forgotten password', 'label', '', 0, ''),
(NULL, 'directory', 'Accound Fields', 'customer_field_first_name', 'allow-required', 'First Name Field', 'Defines whether to allow a First Name field on customer profile', 'enum', 'allow-required,allow-optional,no', 0, ''),
(NULL, 'directory', 'Accound Fields', 'customer_field_last_name', 'allow-required', 'Last Name Field', 'Defines whether to allow a Last Name field on customer profile', 'enum', 'allow-required,allow-optional,no', 0, ''),
(NULL, 'directory', 'Accound Fields', 'customer_field_birth_date', 'allow-optional', 'Birth Date Field', 'Defines whether to allow Birth Date field on customer profile', 'enum', 'allow-required,allow-optional,no', 0, ''),
(NULL, 'directory', 'Accound Fields', 'customer_field_website', 'allow-optional', 'Website Field', 'Defines whether to allow Website field on customer profile', 'enum', 'allow-required,allow-optional,no', 0, ''),
(NULL, 'directory', 'Accound Fields', 'customer_field_company', 'allow-optional', 'Company Field', 'Defines whether to allow Company field on customer profile', 'enum', 'allow-required,allow-optional,no', 0, ''),
(NULL, 'directory', 'Accound Fields', 'customer_field_phone', 'allow-optional', 'Phone Field', 'Defines whether to allow a Phone field on customer profile', 'enum', 'allow-required,allow-optional,no', 0, ''),
(NULL, 'directory', 'Accound Fields', 'customer_field_fax', 'allow-optional', 'Fax Field', 'Defines whether to allow a Fax field on customer profile', 'enum', 'allow-required,allow-optional,no', 0, ''),
(NULL, 'directory', 'Accound Fields', 'customer_field_email', 'allow-required', 'Email Field', 'Defines whether to allow an Email field on customer profile', 'enum', 'allow-required,allow-optional,no', 0, ''),
(NULL, 'directory', 'Accound Fields', 'customer_field_address', 'allow-optional', 'Address Field', 'Defines whether to allow Address field on customer profile', 'enum', 'allow-required,allow-optional,no', 0, ''),
(NULL, 'directory', 'Accound Fields', 'customer_field_address_2', 'allow-optional', 'Address 2 Field', 'Defines whether to allow Address 2 field on customer profile', 'enum', 'allow-optional,no', 0, ''),
(NULL, 'directory', 'Accound Fields', 'customer_field_city', 'allow-optional', 'City Field', 'Defines whether to allow City field on customer profile', 'enum', 'allow-required,allow-optional,no', 0, ''),
(NULL, 'directory', 'Accound Fields', 'customer_field_zip_code', 'allow-optional', 'Zip/Postal Code Field', 'Defines whether to allow Zip/Postal Code field on customer profile', 'enum', 'allow-required,allow-optional,no', 0, ''),
(NULL, 'directory', 'Accound Fields', 'customer_field_country', 'allow-optional', 'Country Field', 'Defines whether to allow Country field on customer profile', 'enum', 'allow-required,allow-optional,no', 0, ''),
(NULL, 'directory', 'Accound Fields', 'customer_field_state', 'allow-optional', 'State/Province Field', 'Defines whether to allow State/Province field on customer profile', 'enum', 'allow-required,allow-optional,no', 0, ''),
(NULL, 'directory', 'Accound Fields', 'customer_field_notifications', 'allow', 'Notifications Field', 'Defines whether to allow Notifications field on customer profile', 'enum', 'allow,no', 0, ''),
(NULL, 'directory', 'Accound Fields', 'customer_field_username', 'allow', 'Username Field', 'Defines whether to allow Username field on customer profile', 'enum', 'allow', 0, ''),
(NULL, 'directory', 'Accound Fields', 'customer_field_password', 'allow', 'Password Field', 'Defines whether to allow Password field on customer profile', 'enum', 'allow', 0, ''),
(NULL, 'directory', 'Accound Fields', 'customer_field_confirm_password', 'allow', 'Confirm Password Field', 'Defines whether to allow Confirm Password field on customer profile', 'enum', 'allow,no', 0, ''),
(NULL, 'directory', 'Accound Fields', 'customer_field_captcha', 'allow', 'Captcha Validation', 'Defines whether to allow Captcha validation on customer registration page', 'enum', 'allow,no', 0, ''),
(NULL, 'directory', 'Advertise Plans', 'modulelink', 'plans/view', 'Advertise Plans Link', 'This link leads to the page where may view all advertise plans', 'label', '', 0, ''),
(NULL, 'directory', 'Reviews', 'reviews_moderation', '0', 'Reviews Pre-Moderation', 'Specifies whether to allow pre-moderation for reviews', 'bool', '', 0, ''),
(NULL, 'directory', 'Reviews', 'moduleblock', 'drawReviewsBlock', 'Recent Reviews', 'Draws recent bottom block', 'label', '', 0, ''),
(NULL, 'directory', 'Inquiries', 'moduleblock', 'drawIncomingJobs', 'Incoming Jobs', 'Draws incoming jobs block', 'label', '', 0, ''),
(NULL, 'directory', 'Inquiries', 'show_send_inquiry', '1', 'Show block Send Inquiry', 'Specifies whether to show block Send an Inquiry', 'bool', '', 0, '');


INSERT INTO `<DB_PREFIX>rss_channels` (`id`, `mode_code`, `channel_code`, `channel_name`, `last_items`, `items_count`, `updated_at`) VALUES
(NULL, 'listings', 'directory', 'Business Directory', '', 10, '2016-01-01 09:00:00'),
(NULL, 'news', 'directory', 'Business Directory', '', 10, '2016-01-01 09:00:00');

UPDATE `<DB_PREFIX>site_info` SET `header`='APPHP Business Directory', `slogan`='Welcome to APPHP Business Directory!', `footer`='APPHP Business Directory © <a class="footer_link" target="_new" href="http://www.apphp.com/php-business-directory/index.php">ApPHP</a>', `meta_title`='APPHP Business Directory', `meta_description`='Business Directory', `meta_keywords`='business directory, business framework, business content management framework, business cms, business directory cms';

INSERT INTO `<DB_PREFIX>privileges` (`id`, `module_code`, `category`, `code`, `name`, `description`) VALUES (NULL, 'directory', 'directory', 'add', 'Add Business Directory', 'Add business directory on the site');
INSERT INTO `<DB_PREFIX>role_privileges` (`id`, `role_id`, `privilege_id`, `is_active`) VALUES (NULL, 1, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 1), (NULL, 2, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 1), (NULL, 3, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 0);
INSERT INTO `<DB_PREFIX>privileges` (`id`, `module_code`, `category`, `code`, `name`, `description`) VALUES (NULL, 'directory', 'directory', 'edit', 'Edit Business Directory', 'Edit business directory on the site');
INSERT INTO `<DB_PREFIX>role_privileges` (`id`, `role_id`, `privilege_id`, `is_active`) VALUES (NULL, 1, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 1), (NULL, 2, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 1), (NULL, 3, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 0);
INSERT INTO `<DB_PREFIX>privileges` (`id`, `module_code`, `category`, `code`, `name`, `description`) VALUES (NULL, 'directory', 'directory', 'delete', 'Delete Business Directory', 'Delete business directory from the site');
INSERT INTO `<DB_PREFIX>role_privileges` (`id`, `role_id`, `privilege_id`, `is_active`) VALUES (NULL, 1, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 1), (NULL, 2, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 1), (NULL, 3, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 0);

INSERT INTO `<DB_PREFIX>privileges` (`id`, `module_code`, `category`, `code`, `name`, `description`) VALUES (NULL, 'directory', 'customer', 'add', 'Add Customer', 'Add customer on the site');
INSERT INTO `<DB_PREFIX>role_privileges` (`id`, `role_id`, `privilege_id`, `is_active`) VALUES (NULL, 1, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 1), (NULL, 2, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 1), (NULL, 3, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 0);
INSERT INTO `<DB_PREFIX>privileges` (`id`, `module_code`, `category`, `code`, `name`, `description`) VALUES (NULL, 'directory', 'customer', 'edit', 'Edit Customer', 'Edit customer on the site');
INSERT INTO `<DB_PREFIX>role_privileges` (`id`, `role_id`, `privilege_id`, `is_active`) VALUES (NULL, 1, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 1), (NULL, 2, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 1), (NULL, 3, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 0);
INSERT INTO `<DB_PREFIX>privileges` (`id`, `module_code`, `category`, `code`, `name`, `description`) VALUES (NULL, 'directory', 'customer', 'delete', 'Delete Customer', 'Delete customer from the site');
INSERT INTO `<DB_PREFIX>role_privileges` (`id`, `role_id`, `privilege_id`, `is_active`) VALUES (NULL, 1, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 1), (NULL, 2, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 1), (NULL, 3, (SELECT MAX(id) FROM `<DB_PREFIX>privileges`), 0);

INSERT INTO `<DB_PREFIX>backend_menus` (`id`, `parent_id`, `url`, `module_code`, `icon`, `is_system`, `is_visible`, `sort_order`) VALUES (NULL, 0, '', 'directory', 'directory.png', 0, 1, 5);
INSERT INTO `<DB_PREFIX>backend_menu_translations` (`id`, `menu_id`, `language_code`, `name`) SELECT NULL, (SELECT MAX(id) FROM `<DB_PREFIX>backend_menus`), code, 'Business Directory' FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>backend_menus` (`id`, `parent_id`, `url`, `module_code`, `icon`, `is_system`, `is_visible`, `sort_order`) VALUES (NULL, (SELECT bm.id FROM `<DB_PREFIX>backend_menus` bm WHERE bm.module_code = 'directory' AND bm.parent_id = 0), 'modules/settings/code/directory', 'directory', '', 0, 1, 0);
INSERT INTO `<DB_PREFIX>backend_menu_translations` (`id`, `menu_id`, `language_code`, `name`) SELECT NULL, (SELECT MAX(id) FROM `<DB_PREFIX>backend_menus`), code, 'Settings' FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>backend_menus` (`id`, `parent_id`, `url`, `module_code`, `icon`, `is_system`, `is_visible`, `sort_order`) VALUES (NULL, (SELECT bm.id FROM `<DB_PREFIX>backend_menus` bm WHERE bm.module_code = 'directory' AND bm.parent_id = 0), 'siteInfoFrontend/siteinfo', 'directory', '', 0, 1, 1);
INSERT INTO `<DB_PREFIX>backend_menu_translations` (`id`, `menu_id`, `language_code`, `name`) SELECT NULL, (SELECT MAX(id) FROM `<DB_PREFIX>backend_menus`), code, 'Site Info' FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>backend_menus` (`id`, `parent_id`, `url`, `module_code`, `icon`, `is_system`, `is_visible`, `sort_order`) VALUES (NULL, (SELECT bm.id FROM `<DB_PREFIX>backend_menus` bm WHERE bm.module_code = 'directory' AND bm.parent_id = 0), 'categories/manage', 'directory', '', 0, 1, 2);
INSERT INTO `<DB_PREFIX>backend_menu_translations` (`id`, `menu_id`, `language_code`, `name`) SELECT NULL, (SELECT MAX(id) FROM `<DB_PREFIX>backend_menus`), code, 'Categories' FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>backend_menus` (`id`, `parent_id`, `url`, `module_code`, `icon`, `is_system`, `is_visible`, `sort_order`) VALUES (NULL, (SELECT bm.id FROM `<DB_PREFIX>backend_menus` bm WHERE bm.module_code = 'directory' AND bm.parent_id = 0), 'regions/manage', 'directory', '', 0, 1, 3);
INSERT INTO `<DB_PREFIX>backend_menu_translations` (`id`, `menu_id`, `language_code`, `name`) SELECT NULL, (SELECT MAX(id) FROM `<DB_PREFIX>backend_menus`), code, 'Locations' FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>backend_menus` (`id`, `parent_id`, `url`, `module_code`, `icon`, `is_system`, `is_visible`, `sort_order`) VALUES (NULL, (SELECT bm.id FROM `<DB_PREFIX>backend_menus` bm WHERE bm.module_code = 'directory' AND bm.parent_id = 0), 'listings/manage', 'directory', '', 0, 1, 4);
INSERT INTO `<DB_PREFIX>backend_menu_translations` (`id`, `menu_id`, `language_code`, `name`) SELECT NULL, (SELECT MAX(id) FROM `<DB_PREFIX>backend_menus`), code, 'Listings' FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>backend_menus` (`id`, `parent_id`, `url`, `module_code`, `icon`, `is_system`, `is_visible`, `sort_order`) VALUES (NULL, (SELECT bm.id FROM `<DB_PREFIX>backend_menus` bm WHERE bm.module_code = 'directory' AND bm.parent_id = 0), 'customers/manage', 'directory', '', 0, 1, 5);
INSERT INTO `<DB_PREFIX>backend_menu_translations` (`id`, `menu_id`, `language_code`, `name`) SELECT NULL, (SELECT MAX(id) FROM `<DB_PREFIX>backend_menus`), code, 'Accounts' FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>backend_menus` (`id`, `parent_id`, `url`, `module_code`, `icon`, `is_system`, `is_visible`, `sort_order`) VALUES (NULL, (SELECT bm.id FROM `<DB_PREFIX>backend_menus` bm WHERE bm.module_code = 'directory' AND bm.parent_id = 0), 'reviews/manage', 'directory', '', 0, 1, 6);
INSERT INTO `<DB_PREFIX>backend_menu_translations` (`id`, `menu_id`, `language_code`, `name`) SELECT NULL, (SELECT MAX(id) FROM `<DB_PREFIX>backend_menus`), code, 'Reviews' FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>backend_menus` (`id`, `parent_id`, `url`, `module_code`, `icon`, `is_system`, `is_visible`, `sort_order`) VALUES (NULL, (SELECT bm.id FROM `<DB_PREFIX>backend_menus` bm WHERE bm.module_code = 'directory' AND bm.parent_id = 0), 'inquiries/manage', 'directory', '', 0, 1, 7);
INSERT INTO `<DB_PREFIX>backend_menu_translations` (`id`, `menu_id`, `language_code`, `name`) SELECT NULL, (SELECT MAX(id) FROM `<DB_PREFIX>backend_menus`), code, 'Inquiries' FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>backend_menus` (`id`, `parent_id`, `url`, `module_code`, `icon`, `is_system`, `is_visible`, `sort_order`) VALUES (NULL, (SELECT bm.id FROM `<DB_PREFIX>backend_menus` bm WHERE bm.module_code = 'directory' AND bm.parent_id = 0), 'plans/manage', 'directory', '', 0, 1, 8);
INSERT INTO `<DB_PREFIX>backend_menu_translations` (`id`, `menu_id`, `language_code`, `name`) SELECT NULL, (SELECT MAX(id) FROM `<DB_PREFIX>backend_menus`), code, 'Advertise Plans' FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>backend_menus` (`id`, `parent_id`, `url`, `module_code`, `icon`, `is_system`, `is_visible`, `sort_order`) VALUES (NULL, (SELECT bm.id FROM `<DB_PREFIX>backend_menus` bm WHERE bm.module_code = 'directory' AND bm.parent_id = 0), 'orders/manage', 'directory', '', 0, 1, 9);
INSERT INTO `<DB_PREFIX>backend_menu_translations` (`id`, `menu_id`, `language_code`, `name`) SELECT NULL, (SELECT MAX(id) FROM `<DB_PREFIX>backend_menus`), code, 'Orders' FROM `<DB_PREFIX>languages`;

INSERT INTO `<DB_PREFIX>search_categories` (`id`, `module_code`, `category_code`, `category_name`, `callback_class`, `callback_method`, `items_count`, `is_active`) VALUES (NULL, 'directory', 'listings', 'Listings', 'Listings', 'search', '20', 1);
INSERT INTO `<DB_PREFIX>search_categories` (`id`, `module_code`, `category_code`, `category_name`, `callback_class`, `callback_method`, `items_count`, `is_active`) VALUES (NULL, 'directory', 'categories', 'Categories', 'Categories', 'search', '20', 1);

INSERT INTO `<DB_PREFIX>frontend_menus` (`id`, `parent_id`, `menu_type`, `module_code`, `link_url`, `link_target`, `placement`, `sort_order`, `access_level`, `is_active`) VALUES (NULL, 0, 'moduleblock', 'directory', 'drawIncomingJobs', '', 'right', 2, 'public', 1);
INSERT INTO `<DB_PREFIX>frontend_menu_translations` (`id`, `menu_id`, `language_code`, `name`) SELECT NULL, (SELECT MAX(id) FROM `<DB_PREFIX>frontend_menus`), code, 'Incoming Jobs' FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>frontend_menus` (`id`, `parent_id`, `menu_type`, `module_code`, `link_url`, `link_target`, `placement`, `sort_order`, `access_level`, `is_active`) VALUES (NULL, 0, 'moduleblock', 'directory', 'drawFeaturedBlock', '', 'right', 1, 'public', 1);
INSERT INTO `<DB_PREFIX>frontend_menu_translations` (`id`, `menu_id`, `language_code`, `name`) SELECT NULL, (SELECT MAX(id) FROM `<DB_PREFIX>frontend_menus`), code, 'Featured Listings' FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>frontend_menus` (`id`, `parent_id`, `menu_type`, `module_code`, `link_url`, `link_target`, `placement`, `sort_order`, `access_level`, `is_active`) VALUES (NULL, 0, 'moduleblock', 'directory', 'drawRecentListingsBlock', '', 'bottom', 1, 'public', 1);
INSERT INTO `<DB_PREFIX>frontend_menu_translations` (`id`, `menu_id`, `language_code`, `name`) SELECT NULL, (SELECT MAX(id) FROM `<DB_PREFIX>frontend_menus`), code, 'Recent Listings' FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>frontend_menus` (`id`, `parent_id`, `menu_type`, `module_code`, `link_url`, `link_target`, `placement`, `sort_order`, `access_level`, `is_active`) VALUES (NULL, 0, 'moduleblock', 'directory', 'drawLoginBlock', '', 'right', 0, 'public', 1);
INSERT INTO `<DB_PREFIX>frontend_menu_translations` (`id`, `menu_id`, `language_code`, `name`) SELECT NULL, (SELECT MAX(id) FROM `<DB_PREFIX>frontend_menus`), code, 'Customers' FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>frontend_menus` (`id`, `parent_id`, `menu_type`, `module_code`, `link_url`, `link_target`, `placement`, `sort_order`, `access_level`, `is_active`) VALUES (NULL, 0, 'moduleblock', 'directory', 'drawReviewsBlock', '', 'bottom', 0, 'public', 1);
INSERT INTO `<DB_PREFIX>frontend_menu_translations` (`id`, `menu_id`, `language_code`, `name`) SELECT NULL, (SELECT MAX(id) FROM `<DB_PREFIX>frontend_menus`), code, 'Recent Reviews' FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>frontend_menus` (`id`, `parent_id`, `menu_type`, `module_code`, `link_url`, `link_target`, `placement`, `sort_order`, `access_level`, `is_active`) VALUES (NULL, 0, 'pagelink', 'directory', 'plans/view', '', 'top', 2, 'public', 1);
INSERT INTO `<DB_PREFIX>frontend_menu_translations` (`id`, `menu_id`, `language_code`, `name`) SELECT NULL, (SELECT MAX(id) FROM `<DB_PREFIX>frontend_menus`), code, 'Advertise Plans' FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>frontend_menus` (`id`, `parent_id`, `menu_type`, `module_code`, `link_url`, `link_target`, `placement`, `sort_order`, `access_level`, `is_active`) VALUES (NULL, 0, 'pagelink', 'directory', 'feeds/listings_rss.xml', '', 'top', 6, 'public', 1);
INSERT INTO `<DB_PREFIX>frontend_menu_translations` (`id`, `menu_id`, `language_code`, `name`) SELECT NULL, (SELECT MAX(id) FROM `<DB_PREFIX>frontend_menus`), code, 'Rss' FROM `<DB_PREFIX>languages`;

INSERT INTO `<DB_PREFIX>email_templates` (`id`, `code`, `module_code`, `is_system`) VALUES
(NULL, 'directory_new_account_created', 'directory', 1),
(NULL, 'directory_new_account_created_by_admin', 'directory', 1),
(NULL, 'directory_password_changed_by_admin', 'directory', 1),
(NULL, 'directory_account_approved_by_admin', 'directory', 1),
(NULL, 'directory_account_created_notify_admin', 'directory', 1),
(NULL, 'directory_account_created_admin_approval', 'directory', 1),
(NULL, 'directory_account_created_email_confirmation', 'directory', 1),
(NULL, 'directory_account_created_auto_approval', 'directory', 1),
(NULL, 'directory_account_removed_by_customer', 'directory', 1),
(NULL, 'directory_new_inquiry', 'directory', 1),
(NULL, 'directory_success_order', 'directory', 1),
(NULL, 'directory_password_forgotten', 'directory', 1);

INSERT INTO `<DB_PREFIX>email_template_translations` (`id`, `template_code`, `language_code`, `template_name`, `template_subject`, `template_content`) SELECT NULL, 'directory_new_account_created', code, 'Email for new customer', 'Your account has been created', 'Dear <b>{FIRST_NAME} {LAST_NAME}</b>!\r\n\r\nCongratulations on creating your new account.\r\n\r\nPlease keep this email for your records, as it contains an important information that you may need, should you ever encounter problems or forget your password.\r\n\r\nYour login: {USERNAME}\r\nYour password: {PASSWORD}\r\n\r\nYou may follow the link below to log into your account:\r\n{BASE URL}index.php?customer=login\r\n\r\nP.S. Remember, we will never sell your personal information or email address.\r\n\r\nEnjoy!\r\n-\r\nSincerely,\r\nCustomer Support' FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>email_template_translations` (`id`, `template_code`, `language_code`, `template_name`, `template_subject`, `template_content`) SELECT NULL, 'directory_new_account_created_by_admin', code, 'New account created (by admin)', 'Your account has been created by administrator', 'Dear <b>{FIRST_NAME} {LAST_NAME}!</b>\r\n\r\nThe {WEB_SITE} Admin has invited you to contribute to our site.\r\n\r\nPlease keep this email for your records, as it contains an important information that you may need, should you ever encounter problems or forget your password.\r\n\r\nYour login: {USERNAME}\r\nYour password: {PASSWORD}\r\n\r\nPlease follow the link below to log into your account: <a href={SITE_URL}customers/login>Login</a>.\r\n\r\nEnjoy!\r\n-\r\nSincerely,\r\nAdministration' FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>email_template_translations` (`id`, `template_code`, `language_code`, `template_name`, `template_subject`, `template_content`) SELECT NULL, 'directory_password_changed_by_admin', code, 'Password changed (by admin)', 'Your password has been changed by admin', 'Hello <b>{FIRST_NAME} {LAST_NAME}!</b>\r\n\r\nYour password has been changed by administrator of the site:\r\n{WEB_SITE}\r\n\r\nBelow your new login info:\r\n-\r\nUsername: {USERNAME} \r\nPassword: {PASSWORD}\r\n\r\n-\r\nBest Regards,\r\nAdministration' FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>email_template_translations` (`id`, `template_code`, `language_code`, `template_name`, `template_subject`, `template_content`) SELECT NULL, 'directory_account_approved_by_admin', code, 'New customer account approved (by admin)', 'Your account has been approved', 'Dear <b>{FIRST_NAME} {LAST_NAME}!</b>\r\n\r\nCongratulations! This e-mail is to confirm that your registration at {WEB_SITE} has been approved.\r\n\r\nYou may now <a href={SITE_URL}customers/login>log into</a> your account.\r\n\r\nThank you for choosing {WEB_SITE}.\r\n-\r\nSincerely,\r\nAdministration' FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>email_template_translations` (`id`, `template_code`, `language_code`, `template_name`, `template_subject`, `template_content`) SELECT NULL, 'directory_account_created_notify_admin', code, 'New customer account created (notify admin)', 'New customer account has been created', "Hello Admin!\r\n\r\nA new customer has been registered on your site.\r\n\r\nThis email contains a customer account details:\r\n\r\nName: {FIRST_NAME} {LAST_NAME}\r\nEmail: {CUSTOMER_EMAIL}\r\nUsername: {USERNAME}\r\n\r\nP.S. Please check if it doesn't require your approval for activation." FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>email_template_translations` (`id`, `template_code`, `language_code`, `template_name`, `template_subject`, `template_content`) SELECT NULL, 'directory_account_created_admin_approval', code, 'New customer account created (admin approval)', 'Your account has been created (admin approval required)', 'Dear <b>{FIRST_NAME} {LAST_NAME}</b>!\r\n\r\nCongratulations on creating your new account.\r\n\r\nPlease keep this email for your records, as it contains an important information that you may need, should you ever encounter problems or forget your password.\r\n\r\nYour login: {USERNAME}\r\nYour password: {PASSWORD}\r\n\r\nAfter your registration is approved by administrator, you could log into your account with a following link:\r\n<a href={SITE_URL}customers/login>Login Here</a>\r\n\r\nP.S. Remember, we will never sell or pass to someone else your personal information or email address.\r\n\r\nEnjoy!\r\n-\r\nSincerely,\r\nCustomer Support' FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>email_template_translations` (`id`, `template_code`, `language_code`, `template_name`, `template_subject`, `template_content`) SELECT NULL, 'directory_account_created_email_confirmation', code, 'New customer account created (email confirmation)', 'Your account has been created (email confirmation required)', 'Dear <b>{FIRST_NAME} {LAST_NAME}</b>!\r\n\r\nCongratulations on creating your new account.\r\n\r\nPlease keep this email for your records, as it contains an important information that you may need, should you ever encounter problems or forget your password.\r\n\r\nYour login: {USERNAME}\r\nYour password: {PASSWORD}\r\n\r\nIn order to become authorized member, you will need to confirm your registration. You may follow the link below to access the confirmation page:\r\n<a href="{SITE_URL}customers/confirmRegistration/code/{REGISTRATION_CODE}">Confirm Registration</a>\r\n\r\nP.S. Remember, we will never sell or pass to someone else your personal information or email address.\r\n\r\nEnjoy!\r\n-\r\nSincerely,\r\nCustomer Support' FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>email_template_translations` (`id`, `template_code`, `language_code`, `template_name`, `template_subject`, `template_content`) SELECT NULL, 'directory_account_created_auto_approval', code, 'New customer account created (auto approval)', 'Your account has been created and activated', 'Dear <b>{FIRST_NAME} {LAST_NAME}</b>!\r\n\r\nCongratulations on creating your new account.\r\n\r\nPlease keep this email for your records, as it contains an important information that you may need, should you ever encounter problems or forget your password.\r\n\r\nYour login: {USERNAME}\r\nYour password: {PASSWORD}\r\n\r\nYou may follow the link below to log into your account:\r\n<a href={SITE_URL}customers/login>Login Here</a>\r\n\r\nP.S. Remember, we will never sell or pass to someone else your personal information or email address.\r\n\r\nEnjoy!\r\n-\r\nSincerely,\r\nCustomer Support' FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>email_template_translations` (`id`, `template_code`, `language_code`, `template_name`, `template_subject`, `template_content`) SELECT NULL, 'directory_account_removed_by_customer', code, 'Account removed (by customer)', 'Your account has been removed', 'Dear {USERNAME}!\r\n\r\nYour account has been successfully removed according to your request.\r\n\r\n-\r\nBest Regards,\r\nAdministration' FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>email_template_translations` (`id`, `template_code`, `language_code`, `template_name`, `template_subject`, `template_content`) SELECT NULL, 'directory_new_inquiry', code, 'New Inquiry', 'You get new Inquiry', 'Dear {FIRST_NAME} {LAST_NAME}!\r\n\r\nThis e-mail is to confirm that we have received new inquiry for you from our visitors.\r\n\r\nYou can now login in to your account to check it:\r\n<a href="{LINK}">Inquiries</a>\r\n\r\nThanks for choosing {WEB_SITE}.\r\n-\r\nSincerely,\r\nAdministration' FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>email_template_translations` (`id`, `template_code`, `language_code`, `template_name`, `template_subject`, `template_content`) SELECT NULL, 'directory_success_order', code, 'Success Order', 'Your order has been placed in our system!', 'Dear {FIRST_NAME} {LAST_NAME}!\r\n\r\nThank you for reservation request!\r\n\r\nYour order <b>{ORDER_NUMBER}</b> has been placed in our system and will be processed shortly.\r\nStatus: {STATUS}\r\n\r\nDate Created: {DATE_CREATED}\r\nPayment Date: {DATE_PAYMENT}\r\nPayment Type: {PAYMENT_TYPE}\r\nCurrency: {CURRENCY}\r\nListing: <a href="{LINK_LISTING}">{LISTING_NAME}</a>\r\nPrice: {PRICE}\r\n\r\nThanks for choosing {WEB_SITE}.\r\n-\r\nSincerely,\r\nAdministration' FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>email_template_translations` (`id`, `template_code`, `language_code`, `template_name`, `template_subject`, `template_content`) SELECT NULL, 'directory_password_forgotten', code, 'Restore forgotten password', 'Forgotten Password', 'Hello!\r\n\r\nYou or someone else asked to restore your login info on our site:\r\n<a href={SITE_URL}customers/login>{WEB_SITE}</a>\r\n\r\nYour new login:\r\n---------------\r\nUsername: {USERNAME}\r\nPassword: {PASSWORD}\r\n\r\n-\r\nSincerely,\r\nAdministration' FROM `<DB_PREFIX>languages`;


DROP TABLE IF EXISTS `<DB_PREFIX>customers`;
CREATE TABLE IF NOT EXISTS `<DB_PREFIX>customers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `account_id` int(10) unsigned NOT NULL DEFAULT 0,
  `group_id` int(10) unsigned NOT NULL DEFAULT 0,
  `first_name` varchar(32) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `last_name` varchar(32) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `birth_date` date NOT NULL DEFAULT '0000-00-00',
  `website` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `company` varchar(128) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `phone` varchar(32) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `fax` varchar(32) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `address` varchar(64) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `address_2` varchar(64) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `city` varchar(64) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `zip_code` varchar(32) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `country_code` varchar(2) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `state` varchar(64) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3;

DROP TABLE IF EXISTS `<DB_PREFIX>customer_groups`;
CREATE TABLE IF NOT EXISTS `<DB_PREFIX>customer_groups` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `is_default` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4;

INSERT INTO `<DB_PREFIX>customer_groups` (`id`, `name`, `description`, `is_default`) VALUES
(1, 'Mens', 'Description group "Mens"', 1),
(2, 'Ladies', 'Description group "Ladies"', 0),
(3, 'Baby', ':)', 0);

INSERT INTO `<DB_PREFIX>accounts` (`id`, `role`, `username`, `password`, `salt`, `token_expires_at`, `email`, `language_code`, `avatar`, `created_at`, `created_ip`, `last_visited_at`, `last_visited_ip`, `notifications`, `notifications_changed_at`, `is_active`, `is_removed`, `comments`, `registration_code`) VALUES (NULL, 'customer', 'customer1', '1921a0fb5aad4577086262cb6fcb4fc1461e4a4cf2f12499593154c0e4f3a9b8', 'aSt/VJyNz1rTQHIMWrSseRHUAbv6cqRj', '', 'test1@exampe.com', 'en', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '000.000.000.000', 0, '0000-00-00 00:00:00', 1, 0, '', '');
INSERT INTO `<DB_PREFIX>customers` (`id`, `account_id`, `group_id`, `first_name`, `last_name`, `birth_date`, `website`, `company`, `phone`, `fax`, `address`, `address_2`, `city`, `zip_code`, `country_code`, `state`) VALUES (1, (SELECT MAX(id) FROM `<DB_PREFIX>accounts`), 1, 'Jon', 'Carter', '1921-01-09', '', '', '', '', '', '', '', '', 'US', '');
INSERT INTO `<DB_PREFIX>accounts` (`id`, `role`, `username`, `password`, `salt`, `token_expires_at`, `email`, `language_code`, `avatar`, `created_at`, `created_ip`, `last_visited_at`, `last_visited_ip`, `notifications`, `notifications_changed_at`, `is_active`, `is_removed`, `comments`, `registration_code`) VALUES (NULL, 'customer', 'customer2', '943d0a294b302095cec71c55a6a06167c850fa9728c1c8bbf5945e804be3027e', 'PaYf7dFoHIlHq8k/zOGlD+Pehbjv+U+P', '', 'test2@exampe.com', 'en', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '000.000.000.000', 0, '0000-00-00 00:00:00', 1, 0, '', '');
INSERT INTO `<DB_PREFIX>customers` (`id`, `account_id`, `group_id`, `first_name`, `last_name`, `birth_date`, `website`, `company`, `phone`, `fax`, `address`, `address_2`, `city`, `zip_code`, `country_code`, `state`) VALUES (2, (SELECT MAX(id) FROM `<DB_PREFIX>accounts`), 2, 'Edward', 'Gracey', '1948-01-07', '', '', '', '', '', '', '', '', 'US', '');


DROP TABLE IF EXISTS `<DB_PREFIX>regions`;
CREATE TABLE `<DB_PREFIX>regions` (
  `id` INTEGER(11) NOT NULL AUTO_INCREMENT,
  `parent_id` INTEGER(11) NOT NULL DEFAULT 0,
  `is_active` TINYINT(1) NOT NULL DEFAULT 0,
  `sort_order` SMALLINT(6) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `parent_id` (`parent_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=33;

INSERT INTO `<DB_PREFIX>regions` (`id`, `parent_id`, `is_active`, `sort_order`) VALUES
(1, 0, 1, 8), (2, 0, 1, 7), (3, 0, 1, 6), (4, 0, 1, 5), (5, 0, 1, 4), (6, 4, 1, 4), (7, 0, 1, 3), (8, 0, 1, 2), (9, 0, 1, 1),
(10, 2, 1, 5), (11, 2, 1, 4), (12, 2, 1, 3), (13, 2, 1, 2), (14, 2, 1, 1), (15, 2, 1, 6),
(16, 1, 1, 5), (17, 1, 1, 4), (18, 1, 1, 3), (19, 1, 1, 2), (20, 1, 1, 1), (21, 1, 1, 6), (22, 1, 1, 7), (23, 1, 1, 8),
(24, 3, 1, 1), (25, 3, 1, 6), (26, 3, 1, 5), (27, 3, 1, 4), (28, 3, 1, 3), (29, 3, 1, 2),
(30, 4, 1, 1), (31, 4, 1, 2), (32, 4, 1, 3);


DROP TABLE IF EXISTS `<DB_PREFIX>region_translations`;
CREATE TABLE `<DB_PREFIX>region_translations` (
  `id` INTEGER(11) NOT NULL AUTO_INCREMENT,
  `region_id` INTEGER(11) NOT NULL DEFAULT 0,
  `name` VARCHAR(70) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `language_code` VARCHAR(2) CHARACTER SET latin1 NOT NULL DEFAULT 'en',
  PRIMARY KEY (`id`),
  KEY `region_id` (`region_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=33;

INSERT INTO `<DB_PREFIX>region_translations` (`id`, `region_id`, `name`, `language_code`) SELECT NULL, 1, 'Bronx', code FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>region_translations` (`id`, `region_id`, `name`, `language_code`) SELECT NULL, 2, 'Brooklyn', code FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>region_translations` (`id`, `region_id`, `name`, `language_code`) SELECT NULL, 3, 'Queens', code FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>region_translations` (`id`, `region_id`, `name`, `language_code`) SELECT NULL, 4, 'Manhattan', code FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>region_translations` (`id`, `region_id`, `name`, `language_code`) SELECT NULL, 5, 'Staten Island', code FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>region_translations` (`id`, `region_id`, `name`, `language_code`) SELECT NULL, 6, 'Uptown neighborhoods', code FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>region_translations` (`id`, `region_id`, `name`, `language_code`) SELECT NULL, 7, 'New Rochelle', code FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>region_translations` (`id`, `region_id`, `name`, `language_code`) SELECT NULL, 8, 'Mount Vernon', code FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>region_translations` (`id`, `region_id`, `name`, `language_code`) SELECT NULL, 9, 'Yonkers', code FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>region_translations` (`id`, `region_id`, `name`, `language_code`) SELECT NULL, 10, 'Northwestern Brooklyn', code FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>region_translations` (`id`, `region_id`, `name`, `language_code`) SELECT NULL, 11, 'Northeastern Brooklyn', code FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>region_translations` (`id`, `region_id`, `name`, `language_code`) SELECT NULL, 12, 'Central Brooklyn', code FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>region_translations` (`id`, `region_id`, `name`, `language_code`) SELECT NULL, 13, 'Western Brooklyn', code FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>region_translations` (`id`, `region_id`, `name`, `language_code`) SELECT NULL, 14, 'Southern Brooklyn', code FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>region_translations` (`id`, `region_id`, `name`, `language_code`) SELECT NULL, 15, 'Eastern Brooklyn', code FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>region_translations` (`id`, `region_id`, `name`, `language_code`) SELECT NULL, 16, 'West Bronx', code FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>region_translations` (`id`, `region_id`, `name`, `language_code`) SELECT NULL, 17, 'East Bronx', code FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>region_translations` (`id`, `region_id`, `name`, `language_code`) SELECT NULL, 18, 'Northwest Bronx', code FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>region_translations` (`id`, `region_id`, `name`, `language_code`) SELECT NULL, 19, 'Southwest Bronx', code FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>region_translations` (`id`, `region_id`, `name`, `language_code`) SELECT NULL, 20, 'Northeast Bronx', code FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>region_translations` (`id`, `region_id`, `name`, `language_code`) SELECT NULL, 21, 'Southeast Bronx', code FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>region_translations` (`id`, `region_id`, `name`, `language_code`) SELECT NULL, 22, 'South Bronx', code FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>region_translations` (`id`, `region_id`, `name`, `language_code`) SELECT NULL, 23, 'North Bronx', code FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>region_translations` (`id`, `region_id`, `name`, `language_code`) SELECT NULL, 24, 'The Rockaways', code FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>region_translations` (`id`, `region_id`, `name`, `language_code`) SELECT NULL, 25, 'Northwestern Queens', code FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>region_translations` (`id`, `region_id`, `name`, `language_code`) SELECT NULL, 26, 'Mid-Queens', code FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>region_translations` (`id`, `region_id`, `name`, `language_code`) SELECT NULL, 27, 'Northeastern Queens', code FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>region_translations` (`id`, `region_id`, `name`, `language_code`) SELECT NULL, 28, 'Southwestern Queens', code FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>region_translations` (`id`, `region_id`, `name`, `language_code`) SELECT NULL, 29, 'Southeastern Queens', code FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>region_translations` (`id`, `region_id`, `name`, `language_code`) SELECT NULL, 30, 'Midtown neighborhoods', code FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>region_translations` (`id`, `region_id`, `name`, `language_code`) SELECT NULL, 31, 'Neighborhoods between Midtown and Downtown', code FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>region_translations` (`id`, `region_id`, `name`, `language_code`) SELECT NULL, 32, 'Downtown neighborhoods', code FROM `<DB_PREFIX>languages`;

DROP TABLE IF EXISTS `<DB_PREFIX>categories`;
CREATE TABLE IF NOT EXISTS `<DB_PREFIX>categories` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) unsigned NOT NULL DEFAULT 0,
  `icon` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `icon_map` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `sort_order` smallint(6) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=61;

INSERT INTO `<DB_PREFIX>categories` (`id`, `parent_id`, `icon`, `icon_map`, `sort_order`) VALUES
(1, 0, 'automotive.png', 'automotive.png', 9),
(2, 0, 'business.png', 'business.png', 8),
(3, 0, 'computers.png', 'computers.png', 7),
(4, 0, 'food.png', 'food.png', 6),
(5, 0, 'furniture.png', 'furniture.png', 5),
(6, 0, 'music.png', 'music.png', 4),
(7, 0, 'pets.png', 'pets.png', 3),
(8, 0, 'shoping.png', 'shoping.png', 2),
(9, 0, 'travel.png', 'travel.png', 1),
(10, 1, '', '', 6),
(11, 1, '', '', 5),
(12, 1, '', '', 4),
(13, 1, '', '', 3),
(14, 1, '', '', 2),
(15, 1, '', '', 1),
(16, 2, '', '', 9),
(17, 2, '', '', 8),
(18, 2, '', '', 7),
(19, 2, '', '', 6),
(20, 2, '', '', 5),
(21, 2, '', '', 4),
(22, 2, '', '', 3),
(23, 2, '', '', 2),
(24, 2, '', '', 1),
(25, 3, '', '', 2),
(26, 3, '', '', 1),
(27, 4, '', '', 5),
(28, 4, '', '', 4),
(29, 4, '', '', 3),
(30, 4, '', '', 2),
(31, 4, '', '', 1),
(32, 6, '', '', 9),
(33, 6, '', '', 8),
(34, 6, '', '', 7),
(35, 6, '', '', 6),
(36, 6, '', '', 5),
(37, 6, '', '', 4),
(38, 6, '', '', 3),
(39, 6, '', '', 2),
(40, 6, '', '', 1),
(41, 7, '', '', 6),
(42, 7, '', '', 5),
(43, 7, '', '', 4),
(44, 7, '', '', 3),
(45, 7, '', '', 2),
(46, 7, '', '', 1),
(47, 8, '', '', 3),
(48, 8, '', '', 2),
(49, 8, '', '', 1),
(50, 9, '', '', 6),
(51, 9, '', '', 5),
(52, 9, '', '', 4),
(53, 9, '', '', 3),
(54, 9, '', '', 2),
(55, 9, '', '', 1),
(56, 10, '', '', 5),
(57, 10, '', '', 4),
(58, 10, '', '', 3),
(59, 10, '', '', 2),
(60, 10, '', '', 1);


DROP TABLE IF EXISTS `<DB_PREFIX>category_translations`;
CREATE TABLE IF NOT EXISTS `<DB_PREFIX>category_translations` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `category_id` int(10) NOT NULL DEFAULT 0,
  `language_code` varchar(2) CHARACTER SET latin1 NOT NULL DEFAULT 'en',
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `description` varchar(512) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `category_id` (`category_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=61;

INSERT INTO `<DB_PREFIX>category_translations` (`id`, `category_id`, `language_code`, `name`, `description`) SELECT NULL, 1, code, 'Automotive', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.' FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>category_translations` (`id`, `category_id`, `language_code`, `name`, `description`) SELECT NULL, 2, code, 'Companies', 'Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur fugiat nullaea commodo cons.' FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>category_translations` (`id`, `category_id`, `language_code`, `name`, `description`) SELECT NULL, 3, code, 'Computers', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.' FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>category_translations` (`id`, `category_id`, `language_code`, `name`, `description`) SELECT NULL, 4, code, 'Food', 'Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum lorem ipsum.' FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>category_translations` (`id`, `category_id`, `language_code`, `name`, `description`) SELECT NULL, 5, code, 'Furniture', 'Ed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium dolore laudan unde omnis atus ipsum ullam corporis.' FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>category_translations` (`id`, `category_id`, `language_code`, `name`, `description`) SELECT NULL, 6, code, 'Music', 'Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia dicta sunt explicabo atae vitae.' FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>category_translations` (`id`, `category_id`, `language_code`, `name`, `description`) SELECT NULL, 7, code, 'Pets', 'Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non aut fugit, sed quia.' FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>category_translations` (`id`, `category_id`, `language_code`, `name`, `description`) SELECT NULL, 8, code, 'Shopping', 'Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam adipisci velit ipsum qui dolorem.' FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>category_translations` (`id`, `category_id`, `language_code`, `name`, `description`) SELECT NULL, 9, code, 'Travel', 'Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae incididunt ut labore et dolore magna aliqua.' FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>category_translations` (`id`, `category_id`, `language_code`, `name`, `description`) SELECT NULL, 10, code, 'Car Accessories', 'Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum lorem ipsum.' FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>category_translations` (`id`, `category_id`, `language_code`, `name`, `description`) SELECT NULL, 11, code, 'Car Dealers', 'Ed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium dolore laudan unde omnis atus ipsum ullam corporis.' FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>category_translations` (`id`, `category_id`, `language_code`, `name`, `description`) SELECT NULL, 12, code, 'Car Licenses', 'Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia dicta sunt explicabo atae vitae.' FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>category_translations` (`id`, `category_id`, `language_code`, `name`, `description`) SELECT NULL, 13, code, 'Car Rentals', 'Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non aut fugit, sed quia.' FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>category_translations` (`id`, `category_id`, `language_code`, `name`, `description`) SELECT NULL, 14, code, 'Car Repairs', 'Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam adipisci velit ipsum qui dolorem.' FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>category_translations` (`id`, `category_id`, `language_code`, `name`, `description`) SELECT NULL, 15, code, 'Car Wash', 'Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae incididunt ut labore et dolore magna aliqua.' FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>category_translations` (`id`, `category_id`, `language_code`, `name`, `description`) SELECT NULL, 16, code, 'Architects', 'Ed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium dolore laudan unde omnis atus ipsum ullam corporis.' FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>category_translations` (`id`, `category_id`, `language_code`, `name`, `description`) SELECT NULL, 17, code, 'Blasting & Demolition', 'Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non aut fugit, sed quia.' FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>category_translations` (`id`, `category_id`, `language_code`, `name`, `description`) SELECT NULL, 18, code, 'Building Materials & Supplies', 'Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae incididunt ut labore et dolore magna aliqua.' FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>category_translations` (`id`, `category_id`, `language_code`, `name`, `description`) SELECT NULL, 19, code, 'Construction Companies', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.' FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>category_translations` (`id`, `category_id`, `language_code`, `name`, `description`) SELECT NULL, 20, code, 'Electricians', 'Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur fugiat nullaea commodo cons.' FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>category_translations` (`id`, `category_id`, `language_code`, `name`, `description`) SELECT NULL, 21, code, 'Engineer', 'Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum lorem ipsum.' FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>category_translations` (`id`, `category_id`, `language_code`, `name`, `description`) SELECT NULL, 22, code, 'Environmental Assessments', 'Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia dicta sunt explicabo atae vitae.' FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>category_translations` (`id`, `category_id`, `language_code`, `name`, `description`) SELECT NULL, 23, code, 'Inspectors', 'Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae incididunt ut labore et dolore magna aliqua.' FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>category_translations` (`id`, `category_id`, `language_code`, `name`, `description`) SELECT NULL, 24, code, 'Plaster & Concrete', 'Ed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium dolore laudan unde omnis atus ipsum ullam corporis.' FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>category_translations` (`id`, `category_id`, `language_code`, `name`, `description`) SELECT NULL, 25, code, 'Computer Programming', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.' FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>category_translations` (`id`, `category_id`, `language_code`, `name`, `description`) SELECT NULL, 26, code, 'Consumer Electronics', 'Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non aut fugit, sed quia.' FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>category_translations` (`id`, `category_id`, `language_code`, `name`, `description`) SELECT NULL, 27, code, 'Desserts', 'Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae incididunt ut labore et dolore magna aliqua.' FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>category_translations` (`id`, `category_id`, `language_code`, `name`, `description`) SELECT NULL, 28, code, 'Fast Foods', 'Ed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium dolore laudan unde omnis atus ipsum ullam corporis.' FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>category_translations` (`id`, `category_id`, `language_code`, `name`, `description`) SELECT NULL, 29, code, 'Groceries', 'Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae incididunt ut labore et dolore magna aliqua.' FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>category_translations` (`id`, `category_id`, `language_code`, `name`, `description`) SELECT NULL, 30, code, 'Beverages', 'Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non aut fugit, sed quia.' FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>category_translations` (`id`, `category_id`, `language_code`, `name`, `description`) SELECT NULL, 31, code, 'Restaurants', 'Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam adipisci velit ipsum qui dolorem.' FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>category_translations` (`id`, `category_id`, `language_code`, `name`, `description`) SELECT NULL, 32, code, 'Rock', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.' FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>category_translations` (`id`, `category_id`, `language_code`, `name`, `description`) SELECT NULL, 33, code, 'Classical', 'Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur fugiat nullaea commodo cons.' FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>category_translations` (`id`, `category_id`, `language_code`, `name`, `description`) SELECT NULL, 34, code, 'Pop', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.' FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>category_translations` (`id`, `category_id`, `language_code`, `name`, `description`) SELECT NULL, 35, code, 'Jazz', 'Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum lorem ipsum.' FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>category_translations` (`id`, `category_id`, `language_code`, `name`, `description`) SELECT NULL, 36, code, 'Disco', 'Ed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium dolore laudan unde omnis atus ipsum ullam corporis.' FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>category_translations` (`id`, `category_id`, `language_code`, `name`, `description`) SELECT NULL, 37, code, 'Texno', 'Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia dicta sunt explicabo atae vitae.' FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>category_translations` (`id`, `category_id`, `language_code`, `name`, `description`) SELECT NULL, 38, code, 'Metal', 'Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non aut fugit, sed quia.' FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>category_translations` (`id`, `category_id`, `language_code`, `name`, `description`) SELECT NULL, 39, code, 'Blues', 'Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam adipisci velit ipsum qui dolorem.' FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>category_translations` (`id`, `category_id`, `language_code`, `name`, `description`) SELECT NULL, 40, code, 'Alternative', 'Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae incididunt ut labore et dolore magna aliqua.' FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>category_translations` (`id`, `category_id`, `language_code`, `name`, `description`) SELECT NULL, 41, code, 'Cats', 'Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum lorem ipsum.' FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>category_translations` (`id`, `category_id`, `language_code`, `name`, `description`) SELECT NULL, 42, code, 'Dogs', 'Ed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium dolore laudan unde omnis atus ipsum ullam corporis.' FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>category_translations` (`id`, `category_id`, `language_code`, `name`, `description`) SELECT NULL, 43, code, 'Birds', 'Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia dicta sunt explicabo atae vitae.' FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>category_translations` (`id`, `category_id`, `language_code`, `name`, `description`) SELECT NULL, 44, code, 'Rabbits', 'Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non aut fugit, sed quia.' FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>category_translations` (`id`, `category_id`, `language_code`, `name`, `description`) SELECT NULL, 45, code, 'Guinea pigs', 'Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam adipisci velit ipsum qui dolorem.' FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>category_translations` (`id`, `category_id`, `language_code`, `name`, `description`) SELECT NULL, 46, code, 'Mice', 'Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae incididunt ut labore et dolore magna aliqua.' FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>category_translations` (`id`, `category_id`, `language_code`, `name`, `description`) SELECT NULL, 47, code, 'Men', 'Ed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium dolore laudan unde omnis atus ipsum ullam corporis.' FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>category_translations` (`id`, `category_id`, `language_code`, `name`, `description`) SELECT NULL, 48, code, 'Women', 'Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non aut fugit, sed quia.' FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>category_translations` (`id`, `category_id`, `language_code`, `name`, `description`) SELECT NULL, 49, code, 'Kids', 'Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae incididunt ut labore et dolore magna aliqua.' FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>category_translations` (`id`, `category_id`, `language_code`, `name`, `description`) SELECT NULL, 50, code, 'Airport shuttle bus & limousines', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.' FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>category_translations` (`id`, `category_id`, `language_code`, `name`, `description`) SELECT NULL, 51, code, 'Cruises & ferries lines', 'Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur fugiat nullaea commodo cons.' FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>category_translations` (`id`, `category_id`, `language_code`, `name`, `description`) SELECT NULL, 52, code, 'Package tours', 'Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum lorem ipsum.' FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>category_translations` (`id`, `category_id`, `language_code`, `name`, `description`) SELECT NULL, 53, code, 'Private tours', 'Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia dicta sunt explicabo atae vitae.' FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>category_translations` (`id`, `category_id`, `language_code`, `name`, `description`) SELECT NULL, 54, code, 'Tours & guides', 'Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae incididunt ut labore et dolore magna aliqua.' FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>category_translations` (`id`, `category_id`, `language_code`, `name`, `description`) SELECT NULL, 55, code, 'Travel insurances', 'Ed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium dolore laudan unde omnis atus ipsum ullam corporis.' FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>category_translations` (`id`, `category_id`, `language_code`, `name`, `description`) SELECT NULL, 56, code, 'Emergency & Breakdown', 'Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur fugiat nullaea commodo cons.' FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>category_translations` (`id`, `category_id`, `language_code`, `name`, `description`) SELECT NULL, 57, code, 'Lighting Accessories', 'Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum lorem ipsum.' FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>category_translations` (`id`, `category_id`, `language_code`, `name`, `description`) SELECT NULL, 58, code, 'In Car Power Accessories', 'Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia dicta sunt explicabo atae vitae.' FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>category_translations` (`id`, `category_id`, `language_code`, `name`, `description`) SELECT NULL, 59, code, 'Baby & Child', 'Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae incididunt ut labore et dolore magna aliqua.' FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>category_translations` (`id`, `category_id`, `language_code`, `name`, `description`) SELECT NULL, 60, code, 'Interior Accessories', 'Ed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium dolore laudan unde omnis atus ipsum ullam corporis.' FROM `<DB_PREFIX>languages`;


DROP TABLE IF EXISTS `<DB_PREFIX>listings`;
CREATE TABLE IF NOT EXISTS `<DB_PREFIX>listings` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `customer_id` int(10) unsigned NOT NULL DEFAULT 0,
  `region_id` int(10) unsigned NOT NULL DEFAULT 0,
  `subregion_id` int(10) unsigned NOT NULL DEFAULT 0,
  `image_file` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `image_file_thumb` varchar(30) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `image_1` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `image_1_thumb` varchar(30) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `image_2` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `image_2_thumb` varchar(30) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `image_3` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `image_3_thumb` varchar(30) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `website_url` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `video_url` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `keywords` varchar(1024) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `region_latitude` varchar(20) NOT NULL DEFAULT 0,
  `region_longitude` varchar(20) NOT NULL DEFAULT 0,
  `business_address` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `business_email` varchar(75) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `business_phone` varchar(32) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `business_fax` varchar(32) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `sort_order` smallint(1) unsigned NOT NULL DEFAULT '0',
  `access_level` tinyint(1) unsigned NOT NULL DEFAULT 0 COMMENT '0 - public, 1 - registered',
  `is_featured` tinyint(1) unsigned NOT NULL DEFAULT 0,
  `is_published` tinyint(1) unsigned NOT NULL DEFAULT 0,
  `date_published` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `finish_publishing` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `is_approved` smallint(1) unsigned NOT NULL DEFAULT 0 COMMENT '0 - pending, 1 - approved, 2 - canceled',
  `advertise_plan_id` tinyint(3) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `customer_id` (`customer_id`),
  KEY `region_id` (`region_id`),
  KEY `subregion_id` (`subregion_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=34;


INSERT INTO `<DB_PREFIX>listings` (`id`, `customer_id`, `region_id`, `subregion_id`, `image_file`, `image_file_thumb`, `website_url`, `region_latitude`, `region_longitude`, `business_address`, `business_email`, `business_phone`, `business_fax`, `sort_order`, `access_level`, `is_featured`, `is_published`, `date_published`, `finish_publishing`, `is_approved`, `advertise_plan_id`, `image_1`, `image_1_thumb`, `image_2`, `image_2_thumb`, `image_3`, `image_3_thumb`, `video_url`, `keywords`) VALUES
(1, 1, 1, 16, 'listing1.png', 'listing1_thumb.png', 'http://www.apphp.com', 40.714931682, -73.953523636, '330 Adams Street Brooklyn, NY 11201, United States', 'directory1@email.com', '044 802 52578', '044 802 52579', 0, 0, 1, 1, NOW(), NOW() + INTERVAL 2 MONTH, 1, 1, '', '', '', '', '', '', '', ''),
(2, 2, 1, 17, 'listing2.png', 'listing2_thumb.png', 'http://www.google.com', 40.695542, -73.995262, '107 Montague Street Brooklyn, NY 11201, United States', 'directory2@email.com', '044 802 53478', '044 802 53479', 0, 1, 0, 1, NOW(), NOW() + INTERVAL 2 DAY, 1, 2, '', '', '', '', '', '', '', ''),
(3, 1, 2, 0, 'listing3.png', 'listing3_thumb.png', 'http://www.apphp.com', 40.71958306715651,-74.0379810333252, '276 Canal Street, New York, NY 10013, United States', 'directory2@email.com', '044 802 53478', '044 802 53479', 0, 0, 0, 1, NOW(), NOW() + INTERVAL 40 DAY, 1, 3, '', '', '', '', '', '', '', ''),
(4, 1, 1, 0, 'listing4.png', 'listing4_thumb.png', 'http://www.apphp.com', 40.7053026089413,-73.95326614379883, '276 Canal Street, New York, NY 10013, United States', 'directory2@email.com', '044 802 53478', '044 802 53479', 0, 0, 1, 1, NOW(), NOW() + INTERVAL 2 YEAR, 1, 4, '', '', '', '', '', '', '', ''),
(5, 2, 2, 0, 'listing5.png', 'listing5_thumb.png', 'http://www.apphp.com', 40.71322392474486,-73.9909029006958, '276 Canal Street, New York, NY 10013, United States', 'directory2@email.com', '044 802 53478', '044 802 53479', 0, 0, 0, 1, NOW(), NOW() + INTERVAL 2 DAY, 1, 1, '', '', '', '', '', '', '', ''),
(6, 1, 3, 0, 'listing6.png', 'listing6_thumb.png', 'http://www.apphp.com', 40.71283357396648,-74.00152444839478, '66 Pearl Street, New York, NY 10000, United States', 'directory2@email.com', '044 802 53478', '044 802 53479', 0, 0, 0, 1, NOW(), NOW() + INTERVAL 20 DAY, 1, 2, '', '', '', '', '', '', '', ''),
(7, 2, 4, 0, 'listing7.png', 'listing7_thumb.png', 'http://www.apphp.com', 40.703135062150984,-73.9937835931778, '66 Pearl Street, New York, NY 10000, United States', 'directory2@email.com', '044 802 53478', '044 802 53479', 0, 0, 0, 1, NOW(), NOW() + INTERVAL 80 DAY, 1, 3, '', '', '', '', '', '', '', ''),
(8, 2, 5, 0, 'listing8.png', 'listing8_thumb.png', 'http://www.apphp.com', 40.70379794042075,-73.98971736431122, '66 Pearl Street, New York, NY 10000, United States', 'directory2@email.com', '044 802 53478', '044 802 53479', 0, 0, 0, 1, NOW(), NOW(), 1, 4, '', '', '', '', '', '', '', ''),
(9, 2, 7, 0, 'listing9.png', 'listing9_thumb.png', 'http://www.apphp.com', 40.70849076720841,-74.01805758476257, '66 Pearl Street, New York, NY 10000, United States', 'directory2@email.com', '044 802 53478', '044 802 53479', 0, 0, 0, 1, NOW(), NOW() - INTERVAL 1 DAY, 1, 1, '', '', '', '', '', '', '', ''),
(10, 1, 1, 0, 'listing10.png', 'listing10_thumb.png', 'http://www.apphp.com', 40.71357361204046,-74.0397834777832, '66 Pearl Street, New York, NY 10000, United States', 'directory2@email.com', '044 802 53478', '044 802 53479', 0, 1, 0, 1, NOW(), NOW() + INTERVAL 15 DAY, 1, 2, '', '', '', '', '', '', '', ''),
(11, 2, 4, 0, 'listing11.png', 'listing11_thumb.png', 'http://www.apphp.com', 40.71309787422285,-74.03395771980286, '66 Pearl Street, New York, NY 10000, United States', 'directory2@email.com', '044 802 53478', '044 802 53479', 0, 1, 0, 1, NOW(), NOW() + INTERVAL 250 DAY, 1, 3, '', '', '', '', '', '', '', ''),
(12, 2, 9, 0, 'listing12.png', 'listing12_thumb.png', 'http://www.apphp.com', 40.7149077945163,-74.00572210550308, '66 Pearl Street, New York, NY 10000, United States', 'directory2@email.com', '044 802 53478', '044 802 53479', 0, 1, 0, 1, NOW(), NOW() + INTERVAL 50 DAY, 1, 1, '', '', '', '', '', '', '', ''),
(13, 1, 9, 0, 'listing13.png', 'listing13_thumb.png', 'http://www.apphp.com', 40.703376017131866,-74.0079402923584, '66 Pearl Street, New York, NY 10000, United States', 'directory2@email.com', '044 802 53478', '044 802 53479', 0, 0, 0, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0, 2, '', '', '', '', '', '', '', ''),
(14, 1, 3, 0, 'listing14.png', 'listing14_thumb.png', 'http://www.apphp.com', 40.71006852376983,-74.01072978973389, '66 Pearl Street, New York, NY 10000, United States', 'directory2@email.com', '044 802 53478', '044 802 53479', 0, 0, 0, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0, 3, '', '', '', '', '', '', '', ''),
(15, 1, 1, 19, 'listing15.png', 'listing15_thumb.png', 'http://www.apphp.com', 40.71138600318452,-73.95681738853455, '66 Pearl Street, New York, NY 10000, United States', 'directory2@email.com', '044 802 53478', '044 802 53479', 0, 0, 0, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0, 1, '', '', '', '', '', '', '', ''),
(16, 2, 1, 0, 'listing16.png', 'listing16_thumb.png', 'http://www.apphp.com', 40.715618839321955,-74.03407037258148, '66 Pearl Street, New York, NY 10000, United States', 'directory2@email.com', '044 802 53478', '044 802 53479', 0, 0, 0, 1, NOW(), NOW() + INTERVAL 30 DAY, 1, 2, '', '', '', '', '', '', '', ''),
(17, 1, 1, 16, 'listing17.png', 'listing17_thumb.png', 'http://www.apphp.com', 40.70436727845332,-73.9669132232666, '66 Pearl Street, New York, NY 10000, United States', 'directory2@email.com', '044 802 53478', '044 802 53479', 0, 1, 0, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0, 3, '', '', '', '', '', '', '', ''),
(18, 2, 1, 17, 'listing18.png', 'listing18_thumb.png', 'http://www.apphp.com', 40.706549018067186,-74.0119394659996, '66 Pearl Street, New York, NY 10000, United States', 'directory2@email.com', '044 802 53478', '044 802 53479', 0, 0, 0, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0, 1, '', '', '', '', '', '', '', ''),
(19, 2, 1, 23, 'listing19.png', 'listing19_thumb.png', 'http://www.apphp.com', 40.70513994371307,-73.96133422851562, '66 Pearl Street, New York, NY 10000, United States', 'directory2@email.com', '044 802 53478', '044 802 53479', 0, 0, 0, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0, 2, '', '', '', '', '', '', '', ''),
(20, 1, 1, 17, 'listing20.png', 'listing20_thumb.png', 'http://www.apphp.com', 40.71288236793894,-73.96618366241455, '66 Pearl Street, New York, NY 10000, United States', 'directory2@email.com', '044 802 53478', '044 802 53479', 0, 0, 1, 1, NOW(), NOW() + INTERVAL 4 DAY, 1, 1, '', '', '', '', '', '', '', ''),
(21, 1, 1, 20, 'listing21.png', 'listing21_thumb.png', 'http://www.apphp.com', 40.72153460029748,-74.03381824493408, '66 Pearl Street, New York, NY 10000, United States', 'directory2@email.com', '044 802 53478', '044 802 53479', 0, 0, 0, 1, NOW(), NOW() + INTERVAL 30 DAY, 1, 2, '', '', '', '', '', '', '', ''),
(22, 1, 1, 19, 'listing22.png', 'listing22_thumb.png', 'http://www.apphp.com', 40.716004880353196,-73.96316471664431, '66 Pearl Street, New York, NY 10000, United States', 'directory2@email.com', '044 802 53478', '044 802 53479', 0, 1, 0, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0, 1, '', '', '', '', '', '', '', ''),
(23, 2, 1, 21, 'listing23.png', 'listing23_thumb.png', 'http://www.apphp.com', 40.71916,-74.001295, '66 Pearl Street, New York, NY 10000, United States', 'directory2@email.com', '044 802 53478', '044 802 53479', 0, 1, 0, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0, 2, '', '', '', '', '', '', '', ''),
(24, 1, 1, 20, 'listing24.png', 'listing24_thumb.png', 'http://www.apphp.com', 40.704928,-73.986336, '10 Jay Street #404, Brooklyn, NY 11201, United States', 'directory2@email.com', '044 802 53478', '044 802 53479', 0, 1, 0, 1, NOW(), NOW() + INTERVAL 14 DAY, 1, 1, '', '', '', '', '', '', '', ''),
(25, 2, 1, 20, 'listing25.png', 'listing25_thumb.png', 'http://www.apphp.com', 40.711093232233985,-73.98279190063477, '23 Park Row, New York, NY 10038, United States', 'directory2@email.com', '044 802 53478', '044 802 53479', 0, 1, 0, 1, NOW(), NOW() + INTERVAL 12 DAY, 1, 1, '', '', '', '', '', '', '', ''),
(26, 1, 1, 23, 'listing26.png', 'listing26_thumb.png', 'http://www.apphp.com', 40.713043,-74.005992, '163 William Street #2 New York, NY 10038, United States', 'directory2@email.com', '044 802 53478', '044 802 53479', 0, 1, 0, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0, 1, '', '', '', '', '', '', '', ''),
(27, 1, 2, 10, 'listing27.png', 'listing27_thumb.png', 'http://www.apphp.com', 40.712415,-73.960541, '749 Driggs Ave Brooklyn, NY 11211', 'directory2@email.com', '044 802 53478', '044 802 53479', 0, 1, 0, 1, NOW(), NOW() + INTERVAL 8 DAY, 1, 1, '', '', '', '', '', '', '', ''),
(28, 1, 2, 12, 'listing28.png', 'listing28_thumb.png', 'http://www.apphp.com', 40.706457520637585,-74.00463581085205, '25 W 25th St 1204, New York, NY 10036', 'directory2@email.com', '044 802 53478', '044 802 53479', 0, 1, 0, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0, 1, '', '', '', '', '', '', '', ''),
(29, 2, 2, 14, 'listing29.png', 'listing29_thumb.png', 'http://www.apphp.com', 40.71408594127331,-73.97918701171875, '71 5th Ave, New York, NY 10003', 'directory2@email.com', '044 802 53478', '044 802 53479', 0, 1, 0, 1, NOW(), NOW() + INTERVAL 16 DAY, 1, 1, '', '', '', '', '', '', '', ''),
(30, 1, 2, 11, 'listing30.png', 'listing30_thumb.png', 'http://www.apphp.com', 40.71746884168838,-73.97819995880127, '10 State Street Manhattan, NY 11201, United States', 'directory2@email.com', '044 802 53478', '044 802 53479', 0, 1, 0, 1, NOW(), NOW() + INTERVAL 18 DAY, 1, 1, '', '', '', '', '', '', '', ''),
(31, 2, 2, 12, 'listing31.png', 'listing31_thumb.png', 'http://www.apphp.com', 40.71492355836883,-74.01167711917117, '86 Murray Street Street Manhattan, NY 11201, United States', 'directory2@email.com', '044 802 53478', '044 802 53479', 0, 0, 1, 1, NOW(), NOW() + INTERVAL 24 DAY, 1, 1, '', '', '', '', '', '', '', ''),
(32, 1, 2, 11, 'listing32.png', 'listing32_thumb.png', 'http://www.apphp.com', 40.70310660279219,-73.98577770892337, '10 Water Street Brooklyn, NY 11201, United States', 'directory2@email.com', '044 802 53478', '044 802 53479', 0, 1, 0, 1, NOW(), NOW() + INTERVAL 11 DAY, 1, 1, '', '', '', '', '', '', '', ''),
(33, 2, 2, 13, 'listing33.png', 'listing33_thumb.png', 'http://www.apphp.com', 40.72106298503115,-74.04534101486206, '184 Bay Street Jersey City, NJ 07302, United States', 'directory2@email.com', '044 802 53478', '044 802 53479', 0, 0, 0, 1, NOW(), NOW() + INTERVAL 2 DAY, 1, 1, '', '', '', '', '', '', '', '');


DROP TABLE IF EXISTS `<DB_PREFIX>listings_categories`;
CREATE TABLE IF NOT EXISTS `<DB_PREFIX>listings_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(10) unsigned NOT NULL DEFAULT 0,
  `listing_id` int(10) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `category_id` (`category_id`),
  KEY `listing_id` (`listing_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=46;

INSERT INTO `<DB_PREFIX>listings_categories` (`id`, `category_id`, `listing_id`) VALUES (1, 2, 1), (2, 2, 2), (3, 11, 2), (4, 32, 3), (5, 36, 3), (6, 34, 3), (7, 38, 3), (8, 13, 4), (9, 8, 5), (10, 28, 6), (11, 54, 7), (12, 5, 8), (13, 6, 9), (14, 47, 10), (15, 48, 10), (16, 8, 1),
(17, 49, 12), (18, 25, 13), (19, 25, 14), (20, 26, 14), (21, 2, 15), (22, 31, 16), (23, 28, 17), (24, 16, 18), (25, 18, 19), (26, 2, 20), (27, 19, 21), (28, 59, 22), (29, 60, 23), (30, 47, 24), (31, 49, 24), (32, 48, 24), (33, 3, 25), (34, 20, 26), (35, 17, 27), (36, 19, 27),
(37, 22, 28), (38, 10, 29), (39, 12, 29), (40, 13, 30), (41, 11, 30), (42, 1, 31), (43, 14, 32), (44, 15, 33), (45, 12, 33);


DROP TABLE IF EXISTS `<DB_PREFIX>listing_translations`;
CREATE TABLE IF NOT EXISTS `<DB_PREFIX>listing_translations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `listing_id` int(10) unsigned NOT NULL DEFAULT 0,
  `language_code` varchar(2) CHARACTER SET latin1 NOT NULL DEFAULT 'en',
  `business_name` varchar(125) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `business_description` varchar(1024) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `listing_id` (`listing_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=34;

INSERT INTO `<DB_PREFIX>listing_translations` (`id`, `listing_id`, `language_code`, `business_name`, `business_description`) SELECT NULL, 1, code, 'AVA Consult Center', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Integer leo est, lobortis non egestas eget, interdum vel eros.Curabitur id erat lorem. Pellentesque tincidunt pellentesque augue condimentum varius. Suspendisse potenti. Maecenas tristique, purus vel consectetur imperdiet, nunc velit euismod libero, nec sollicitudin metus lectus ac nunc. Cras gravida metus quis ante feugiat rhoncus. Nunc pellentesque massa id tortor porta in sodales dui dictum. Morbi diam justo, malesuada sed lacinia id, ultricies et elit.' FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>listing_translations` (`id`, `listing_id`, `language_code`, `business_name`, `business_description`) SELECT NULL, 2, code, "Viktor's Cars", "Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Integer leo est, lobortis non egestas eget, interdum vel eros.Curabitur id erat lorem. Pellentesque tincidunt pellentesque augue condimentum varius. Suspendisse potenti. Maecenas tristique, purus vel consectetur imperdiet, nunc velit euismod libero, nec sollicitudin metus lectus ac nunc. Cras gravida metus quis ante feugiat rhoncus. Nunc pellentesque massa id tortor porta in sodales dui dictum. Morbi diam justo, malesuada sed lacinia id, ultricies et elit." FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>listing_translations` (`id`, `listing_id`, `language_code`, `business_name`, `business_description`) SELECT NULL, 3, code, "Dance & Relax", "Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Integer leo est, lobortis non egestas eget, interdum vel eros.Curabitur id erat lorem. Pellentesque tincidunt pellentesque augue condimentum varius. Suspendisse potenti. Maecenas tristique, purus vel consectetur imperdiet, nunc velit euismod libero, nec sollicitudin metus lectus ac nunc. Cras gravida metus quis ante feugiat rhoncus. Nunc pellentesque massa id tortor porta in sodales dui dictum. Morbi diam justo, malesuada sed lacinia id, ultricies et elit." FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>listing_translations` (`id`, `listing_id`, `language_code`, `business_name`, `business_description`) SELECT NULL, 4, code, "Bike PRO", "Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Integer leo est, lobortis non egestas eget, interdum vel eros.Curabitur id erat lorem. Pellentesque tincidunt pellentesque augue condimentum varius. Suspendisse potenti. Maecenas tristique, purus vel consectetur imperdiet, nunc velit euismod libero, nec sollicitudin metus lectus ac nunc. Cras gravida metus quis ante feugiat rhoncus. Nunc pellentesque massa id tortor porta in sodales dui dictum. Morbi diam justo, malesuada sed lacinia id, ultricies et elit." FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>listing_translations` (`id`, `listing_id`, `language_code`, `business_name`, `business_description`) SELECT NULL, 5, code, "Golf Game", "Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Integer leo est, lobortis non egestas eget, interdum vel eros.Curabitur id erat lorem. Pellentesque tincidunt pellentesque augue condimentum varius. Suspendisse potenti. Maecenas tristique, purus vel consectetur imperdiet, nunc velit euismod libero, nec sollicitudin metus lectus ac nunc. Cras gravida metus quis ante feugiat rhoncus. Nunc pellentesque massa id tortor porta in sodales dui dictum. Morbi diam justo, malesuada sed lacinia id, ultricies et elit." FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>listing_translations` (`id`, `listing_id`, `language_code`, `business_name`, `business_description`) SELECT NULL, 6, code, "Pizza House JARO", "Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Integer leo est, lobortis non egestas eget, interdum vel eros.Curabitur id erat lorem. Pellentesque tincidunt pellentesque augue condimentum varius. Suspendisse potenti. Maecenas tristique, purus vel consectetur imperdiet, nunc velit euismod libero, nec sollicitudin metus lectus ac nunc. Cras gravida metus quis ante feugiat rhoncus. Nunc pellentesque massa id tortor porta in sodales dui dictum. Morbi diam justo, malesuada sed lacinia id, ultricies et elit." FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>listing_translations` (`id`, `listing_id`, `language_code`, `business_name`, `business_description`) SELECT NULL, 7, code, "Age Travel", "Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Integer leo est, lobortis non egestas eget, interdum vel eros.Curabitur id erat lorem. Pellentesque tincidunt pellentesque augue condimentum varius. Suspendisse potenti. Maecenas tristique, purus vel consectetur imperdiet, nunc velit euismod libero, nec sollicitudin metus lectus ac nunc. Cras gravida metus quis ante feugiat rhoncus. Nunc pellentesque massa id tortor porta in sodales dui dictum. Morbi diam justo, malesuada sed lacinia id, ultricies et elit." FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>listing_translations` (`id`, `listing_id`, `language_code`, `business_name`, `business_description`) SELECT NULL, 8, code, "School of Golf", "Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Integer leo est, lobortis non egestas eget, interdum vel eros.Curabitur id erat lorem. Pellentesque tincidunt pellentesque augue condimentum varius. Suspendisse potenti. Maecenas tristique, purus vel consectetur imperdiet, nunc velit euismod libero, nec sollicitudin metus lectus ac nunc. Cras gravida metus quis ante feugiat rhoncus. Nunc pellentesque massa id tortor porta in sodales dui dictum. Morbi diam justo, malesuada sed lacinia id, ultricies et elit." FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>listing_translations` (`id`, `listing_id`, `language_code`, `business_name`, `business_description`) SELECT NULL, 9, code, "Step 2 Step", "Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Integer leo est, lobortis non egestas eget, interdum vel eros.Curabitur id erat lorem. Pellentesque tincidunt pellentesque augue condimentum varius. Suspendisse potenti. Maecenas tristique, purus vel consectetur imperdiet, nunc velit euismod libero, nec sollicitudin metus lectus ac nunc. Cras gravida metus quis ante feugiat rhoncus. Nunc pellentesque massa id tortor porta in sodales dui dictum. Morbi diam justo, malesuada sed lacinia id, ultricies et elit." FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>listing_translations` (`id`, `listing_id`, `language_code`, `business_name`, `business_description`) SELECT NULL, 10, code, "Ride Shop", "Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Integer leo est, lobortis non egestas eget, interdum vel eros.Curabitur id erat lorem. Pellentesque tincidunt pellentesque augue condimentum varius. Suspendisse potenti. Maecenas tristique, purus vel consectetur imperdiet, nunc velit euismod libero, nec sollicitudin metus lectus ac nunc. Cras gravida metus quis ante feugiat rhoncus. Nunc pellentesque massa id tortor porta in sodales dui dictum. Morbi diam justo, malesuada sed lacinia id, ultricies et elit." FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>listing_translations` (`id`, `listing_id`, `language_code`, `business_name`, `business_description`) SELECT NULL, 11, code, "KE Shop", "Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Integer leo est, lobortis non egestas eget, interdum vel eros.Curabitur id erat lorem. Pellentesque tincidunt pellentesque augue condimentum varius. Suspendisse potenti. Maecenas tristique, purus vel consectetur imperdiet, nunc velit euismod libero, nec sollicitudin metus lectus ac nunc. Cras gravida metus quis ante feugiat rhoncus. Nunc pellentesque massa id tortor porta in sodales dui dictum. Morbi diam justo, malesuada sed lacinia id, ultricies et elit." FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>listing_translations` (`id`, `listing_id`, `language_code`, `business_name`, `business_description`) SELECT NULL, 12, code, "X-Buy Shop", "Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Integer leo est, lobortis non egestas eget, interdum vel eros.Curabitur id erat lorem. Pellentesque tincidunt pellentesque augue condimentum varius. Suspendisse potenti. Maecenas tristique, purus vel consectetur imperdiet, nunc velit euismod libero, nec sollicitudin metus lectus ac nunc. Cras gravida metus quis ante feugiat rhoncus. Nunc pellentesque massa id tortor porta in sodales dui dictum. Morbi diam justo, malesuada sed lacinia id, ultricies et elit." FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>listing_translations` (`id`, `listing_id`, `language_code`, `business_name`, `business_description`) SELECT NULL, 13, code, "IT Solving PRO", "Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Integer leo est, lobortis non egestas eget, interdum vel eros.Curabitur id erat lorem. Pellentesque tincidunt pellentesque augue condimentum varius. Suspendisse potenti. Maecenas tristique, purus vel consectetur imperdiet, nunc velit euismod libero, nec sollicitudin metus lectus ac nunc. Cras gravida metus quis ante feugiat rhoncus. Nunc pellentesque massa id tortor porta in sodales dui dictum. Morbi diam justo, malesuada sed lacinia id, ultricies et elit." FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>listing_translations` (`id`, `listing_id`, `language_code`, `business_name`, `business_description`) SELECT NULL, 14, code, "IT MAX", "Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Integer leo est, lobortis non egestas eget, interdum vel eros.Curabitur id erat lorem. Pellentesque tincidunt pellentesque augue condimentum varius. Suspendisse potenti. Maecenas tristique, purus vel consectetur imperdiet, nunc velit euismod libero, nec sollicitudin metus lectus ac nunc. Cras gravida metus quis ante feugiat rhoncus. Nunc pellentesque massa id tortor porta in sodales dui dictum. Morbi diam justo, malesuada sed lacinia id, ultricies et elit." FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>listing_translations` (`id`, `listing_id`, `language_code`, `business_name`, `business_description`) SELECT NULL, 15, code, "Dr. Martinko", "Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Integer leo est, lobortis non egestas eget, interdum vel eros.Curabitur id erat lorem. Pellentesque tincidunt pellentesque augue condimentum varius. Suspendisse potenti. Maecenas tristique, purus vel consectetur imperdiet, nunc velit euismod libero, nec sollicitudin metus lectus ac nunc. Cras gravida metus quis ante feugiat rhoncus. Nunc pellentesque massa id tortor porta in sodales dui dictum. Morbi diam justo, malesuada sed lacinia id, ultricies et elit." FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>listing_translations` (`id`, `listing_id`, `language_code`, `business_name`, `business_description`) SELECT NULL, 16, code, "Art Restaurant", "Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Integer leo est, lobortis non egestas eget, interdum vel eros.Curabitur id erat lorem. Pellentesque tincidunt pellentesque augue condimentum varius. Suspendisse potenti. Maecenas tristique, purus vel consectetur imperdiet, nunc velit euismod libero, nec sollicitudin metus lectus ac nunc. Cras gravida metus quis ante feugiat rhoncus. Nunc pellentesque massa id tortor porta in sodales dui dictum. Morbi diam justo, malesuada sed lacinia id, ultricies et elit." FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>listing_translations` (`id`, `listing_id`, `language_code`, `business_name`, `business_description`) SELECT NULL, 17, code, "Corner Fast Food", "Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Integer leo est, lobortis non egestas eget, interdum vel eros.Curabitur id erat lorem. Pellentesque tincidunt pellentesque augue condimentum varius. Suspendisse potenti. Maecenas tristique, purus vel consectetur imperdiet, nunc velit euismod libero, nec sollicitudin metus lectus ac nunc. Cras gravida metus quis ante feugiat rhoncus. Nunc pellentesque massa id tortor porta in sodales dui dictum. Morbi diam justo, malesuada sed lacinia id, ultricies et elit." FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>listing_translations` (`id`, `listing_id`, `language_code`, `business_name`, `business_description`) SELECT NULL, 18, code, "St. Patrick's School", "Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Integer leo est, lobortis non egestas eget, interdum vel eros.Curabitur id erat lorem. Pellentesque tincidunt pellentesque augue condimentum varius. Suspendisse potenti. Maecenas tristique, purus vel consectetur imperdiet, nunc velit euismod libero, nec sollicitudin metus lectus ac nunc. Cras gravida metus quis ante feugiat rhoncus. Nunc pellentesque massa id tortor porta in sodales dui dictum. Morbi diam justo, malesuada sed lacinia id, ultricies et elit." FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>listing_translations` (`id`, `listing_id`, `language_code`, `business_name`, `business_description`) SELECT NULL, 19, code, "Brooklyn Public School", "Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Integer leo est, lobortis non egestas eget, interdum vel eros.Curabitur id erat lorem. Pellentesque tincidunt pellentesque augue condimentum varius. Suspendisse potenti. Maecenas tristique, purus vel consectetur imperdiet, nunc velit euismod libero, nec sollicitudin metus lectus ac nunc. Cras gravida metus quis ante feugiat rhoncus. Nunc pellentesque massa id tortor porta in sodales dui dictum. Morbi diam justo, malesuada sed lacinia id, ultricies et elit." FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>listing_translations` (`id`, `listing_id`, `language_code`, `business_name`, `business_description`) SELECT NULL, 20, code, "Dr. Sabol", "Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Integer leo est, lobortis non egestas eget, interdum vel eros.Curabitur id erat lorem. Pellentesque tincidunt pellentesque augue condimentum varius. Suspendisse potenti. Maecenas tristique, purus vel consectetur imperdiet, nunc velit euismod libero, nec sollicitudin metus lectus ac nunc. Cras gravida metus quis ante feugiat rhoncus. Nunc pellentesque massa id tortor porta in sodales dui dictum. Morbi diam justo, malesuada sed lacinia id, ultricies et elit." FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>listing_translations` (`id`, `listing_id`, `language_code`, `business_name`, `business_description`) SELECT NULL, 21, code, "Dr. Martin", "Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Integer leo est, lobortis non egestas eget, interdum vel eros.Curabitur id erat lorem. Pellentesque tincidunt pellentesque augue condimentum varius. Suspendisse potenti. Maecenas tristique, purus vel consectetur imperdiet, nunc velit euismod libero, nec sollicitudin metus lectus ac nunc. Cras gravida metus quis ante feugiat rhoncus. Nunc pellentesque massa id tortor porta in sodales dui dictum. Morbi diam justo, malesuada sed lacinia id, ultricies et elit." FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>listing_translations` (`id`, `listing_id`, `language_code`, `business_name`, `business_description`) SELECT NULL, 22, code, "Trip & Travel", "Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Integer leo est, lobortis non egestas eget, interdum vel eros.Curabitur id erat lorem. Pellentesque tincidunt pellentesque augue condimentum varius. Suspendisse potenti. Maecenas tristique, purus vel consectetur imperdiet, nunc velit euismod libero, nec sollicitudin metus lectus ac nunc. Cras gravida metus quis ante feugiat rhoncus. Nunc pellentesque massa id tortor porta in sodales dui dictum. Morbi diam justo, malesuada sed lacinia id, ultricies et elit." FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>listing_translations` (`id`, `listing_id`, `language_code`, `business_name`, `business_description`) SELECT NULL, 23, code, "Tour 4 You", "Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Integer leo est, lobortis non egestas eget, interdum vel eros.Curabitur id erat lorem. Pellentesque tincidunt pellentesque augue condimentum varius. Suspendisse potenti. Maecenas tristique, purus vel consectetur imperdiet, nunc velit euismod libero, nec sollicitudin metus lectus ac nunc. Cras gravida metus quis ante feugiat rhoncus. Nunc pellentesque massa id tortor porta in sodales dui dictum. Morbi diam justo, malesuada sed lacinia id, ultricies et elit." FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>listing_translations` (`id`, `listing_id`, `language_code`, `business_name`, `business_description`) SELECT NULL, 24, code, "BB Shoping Center", "Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Integer leo est, lobortis non egestas eget, interdum vel eros.Curabitur id erat lorem. Pellentesque tincidunt pellentesque augue condimentum varius. Suspendisse potenti. Maecenas tristique, purus vel consectetur imperdiet, nunc velit euismod libero, nec sollicitudin metus lectus ac nunc. Cras gravida metus quis ante feugiat rhoncus. Nunc pellentesque massa id tortor porta in sodales dui dictum. Morbi diam justo, malesuada sed lacinia id, ultricies et elit." FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>listing_translations` (`id`, `listing_id`, `language_code`, `business_name`, `business_description`) SELECT NULL, 25, code, "IT Shop NYC", "Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Integer leo est, lobortis non egestas eget, interdum vel eros.Curabitur id erat lorem. Pellentesque tincidunt pellentesque augue condimentum varius. Suspendisse potenti. Maecenas tristique, purus vel consectetur imperdiet, nunc velit euismod libero, nec sollicitudin metus lectus ac nunc. Cras gravida metus quis ante feugiat rhoncus. Nunc pellentesque massa id tortor porta in sodales dui dictum. Morbi diam justo, malesuada sed lacinia id, ultricies et elit." FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>listing_translations` (`id`, `listing_id`, `language_code`, `business_name`, `business_description`) SELECT NULL, 26, code, "Pace Public School", "Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Integer leo est, lobortis non egestas eget, interdum vel eros.Curabitur id erat lorem. Pellentesque tincidunt pellentesque augue condimentum varius. Suspendisse potenti. Maecenas tristique, purus vel consectetur imperdiet, nunc velit euismod libero, nec sollicitudin metus lectus ac nunc. Cras gravida metus quis ante feugiat rhoncus. Nunc pellentesque massa id tortor porta in sodales dui dictum. Morbi diam justo, malesuada sed lacinia id, ultricies et elit." FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>listing_translations` (`id`, `listing_id`, `language_code`, `business_name`, `business_description`) SELECT NULL, 27, code, "Business PRO", "Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Integer leo est, lobortis non egestas eget, interdum vel eros.Curabitur id erat lorem. Pellentesque tincidunt pellentesque augue condimentum varius. Suspendisse potenti. Maecenas tristique, purus vel consectetur imperdiet, nunc velit euismod libero, nec sollicitudin metus lectus ac nunc. Cras gravida metus quis ante feugiat rhoncus. Nunc pellentesque massa id tortor porta in sodales dui dictum. Morbi diam justo, malesuada sed lacinia id, ultricies et elit." FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>listing_translations` (`id`, `listing_id`, `language_code`, `business_name`, `business_description`) SELECT NULL, 28, code, "Pro Agent", "Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Integer leo est, lobortis non egestas eget, interdum vel eros.Curabitur id erat lorem. Pellentesque tincidunt pellentesque augue condimentum varius. Suspendisse potenti. Maecenas tristique, purus vel consectetur imperdiet, nunc velit euismod libero, nec sollicitudin metus lectus ac nunc. Cras gravida metus quis ante feugiat rhoncus. Nunc pellentesque massa id tortor porta in sodales dui dictum. Morbi diam justo, malesuada sed lacinia id, ultricies et elit." FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>listing_translations` (`id`, `listing_id`, `language_code`, `business_name`, `business_description`) SELECT NULL, 29, code, "LLA - Car", "Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Integer leo est, lobortis non egestas eget, interdum vel eros.Curabitur id erat lorem. Pellentesque tincidunt pellentesque augue condimentum varius. Suspendisse potenti. Maecenas tristique, purus vel consectetur imperdiet, nunc velit euismod libero, nec sollicitudin metus lectus ac nunc. Cras gravida metus quis ante feugiat rhoncus. Nunc pellentesque massa id tortor porta in sodales dui dictum. Morbi diam justo, malesuada sed lacinia id, ultricies et elit." FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>listing_translations` (`id`, `listing_id`, `language_code`, `business_name`, `business_description`) SELECT NULL, 30, code, "Car PRO", "Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Integer leo est, lobortis non egestas eget, interdum vel eros.Curabitur id erat lorem. Pellentesque tincidunt pellentesque augue condimentum varius. Suspendisse potenti. Maecenas tristique, purus vel consectetur imperdiet, nunc velit euismod libero, nec sollicitudin metus lectus ac nunc. Cras gravida metus quis ante feugiat rhoncus. Nunc pellentesque massa id tortor porta in sodales dui dictum. Morbi diam justo, malesuada sed lacinia id, ultricies et elit." FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>listing_translations` (`id`, `listing_id`, `language_code`, `business_name`, `business_description`) SELECT NULL, 31, code, "Car AVA", "Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Integer leo est, lobortis non egestas eget, interdum vel eros.Curabitur id erat lorem. Pellentesque tincidunt pellentesque augue condimentum varius. Suspendisse potenti. Maecenas tristique, purus vel consectetur imperdiet, nunc velit euismod libero, nec sollicitudin metus lectus ac nunc. Cras gravida metus quis ante feugiat rhoncus. Nunc pellentesque massa id tortor porta in sodales dui dictum. Morbi diam justo, malesuada sed lacinia id, ultricies et elit." FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>listing_translations` (`id`, `listing_id`, `language_code`, `business_name`, `business_description`) SELECT NULL, 32, code, "WRC Cars", "Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Integer leo est, lobortis non egestas eget, interdum vel eros.Curabitur id erat lorem. Pellentesque tincidunt pellentesque augue condimentum varius. Suspendisse potenti. Maecenas tristique, purus vel consectetur imperdiet, nunc velit euismod libero, nec sollicitudin metus lectus ac nunc. Cras gravida metus quis ante feugiat rhoncus. Nunc pellentesque massa id tortor porta in sodales dui dictum. Morbi diam justo, malesuada sed lacinia id, ultricies et elit." FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>listing_translations` (`id`, `listing_id`, `language_code`, `business_name`, `business_description`) SELECT NULL, 33, code, "Cypro Car Consult", "Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Integer leo est, lobortis non egestas eget, interdum vel eros.Curabitur id erat lorem. Pellentesque tincidunt pellentesque augue condimentum varius. Suspendisse potenti. Maecenas tristique, purus vel consectetur imperdiet, nunc velit euismod libero, nec sollicitudin metus lectus ac nunc. Cras gravida metus quis ante feugiat rhoncus. Nunc pellentesque massa id tortor porta in sodales dui dictum. Morbi diam justo, malesuada sed lacinia id, ultricies et elit." FROM `<DB_PREFIX>languages`;


DROP TABLE IF EXISTS `<DB_PREFIX>advertise_plans`;
CREATE TABLE IF NOT EXISTS `<DB_PREFIX>advertise_plans` (
  `id` tinyint(3) NOT NULL AUTO_INCREMENT,
  `categories_count` tinyint(1) unsigned NOT NULL DEFAULT 1,
  `logo` tinyint(1) unsigned NOT NULL DEFAULT 1,
  `images_count` tinyint(1) unsigned NOT NULL DEFAULT 0,
  `business_description` tinyint(1) unsigned NOT NULL DEFAULT 1,
  `email` tinyint(1) unsigned NOT NULL DEFAULT 0,
  `phone` tinyint(1) unsigned NOT NULL DEFAULT 1,
  `fax` tinyint(1) unsigned NOT NULL DEFAULT 0,
  `address` tinyint(1) unsigned NOT NULL DEFAULT 1,
  `website` tinyint(1) unsigned NOT NULL DEFAULT 0,
  `video_link` tinyint(1) unsigned NOT NULL DEFAULT 0,
  `map` tinyint(1) unsigned NOT NULL DEFAULT 0,
  `keywords_count` tinyint(1) unsigned NOT NULL DEFAULT 1,
  `inquiries_count` tinyint(1) NOT NULL DEFAULT 1,
  `inquiry_button` tinyint(1) unsigned NOT NULL DEFAULT 1,
  `rating_button` tinyint(1) unsigned NOT NULL DEFAULT 1,
  `price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `open_comments` tinyint(1) unsigned NOT NULL DEFAULT 0,
  `duration` smallint(6) NOT NULL DEFAULT 1 COMMENT 'in days',
  `is_default` tinyint(1) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5;

INSERT INTO `<DB_PREFIX>advertise_plans` (`id`, `categories_count`, `logo`, `images_count`, `business_description`, `email`, `phone`, `fax`, `address`, `website`, `video_link`, `map`, `keywords_count`, `inquiries_count`, `inquiry_button`, `rating_button`, `price`, `open_comments`, `duration`, `is_default`) VALUES
(1, 1, 1, 0, 1, 1, 1, 0, 1, 0, 0, 1, 1, 1, 0, 0, '0.00', 0, 90, 1),
(2, 1, 1, 1, 1, 1, 1, 1, 1, 0, 0, 1, 3, 3, 0, 0, '10.00', 1, 365, 0),
(3, 3, 1, 2, 1, 1, 1, 1, 1, 1, 0, 1, 15, 20, 1, 1, '20.00', 1, 1095, 0),
(4, 5, 1, 3, 1, 1, 1, 1, 1, 1, 1, 1, 50, -1, 1, 1, '30.00', 1, -1, 0);


DROP TABLE IF EXISTS `<DB_PREFIX>advertise_plan_translations`;
CREATE TABLE IF NOT EXISTS `<DB_PREFIX>advertise_plan_translations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `advertise_plan_id` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `language_code` varchar(2) CHARACTER SET latin1 NOT NULL DEFAULT 'en',
  `name` varchar(125) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `advertise_plan_id` (`advertise_plan_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5;

INSERT INTO `<DB_PREFIX>advertise_plan_translations` (`id`, `advertise_plan_id`, `language_code`, `name`, `description`) SELECT NULL, 1, code, 'Free', 'Free advertise plan. This plan offers a minimal features, but free.' FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>advertise_plan_translations` (`id`, `advertise_plan_id`, `language_code`, `name`, `description`) SELECT NULL, 2, code, 'Bronze', 'A step up from free advertise plan. More features available.' FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>advertise_plan_translations` (`id`, `advertise_plan_id`, `language_code`, `name`, `description`) SELECT NULL, 3, code, 'Silver', 'More features and details are allowed and are listed higher in results.' FROM `<DB_PREFIX>languages`;
INSERT INTO `<DB_PREFIX>advertise_plan_translations` (`id`, `advertise_plan_id`, `language_code`, `name`, `description`) SELECT NULL, 4, code, 'Gold', 'This advertise plan provides maximum features and benefits.' FROM `<DB_PREFIX>languages`;


DROP TABLE IF EXISTS `<DB_PREFIX>orders`;
CREATE TABLE IF NOT EXISTS `<DB_PREFIX>orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_number` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `order_description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `order_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `listing_id` int(11) NOT NULL DEFAULT 0,
  `vat_percent` decimal(4,2) NOT NULL DEFAULT '0.00',
  `vat_fee` decimal(10,2) NOT NULL DEFAULT '0.00',
  `total_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `currency` int(11) NOT NULL DEFAULT 0,
  `advertise_plan_id` tinyint(3) unsigned NOT NULL DEFAULT 0,
  `customer_id` int(11) NOT NULL DEFAULT 0,
  `transaction_number` varchar(30) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `created_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `payment_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `payment_id` tinyint(1) unsigned NOT NULL DEFAULT 0,
  `payment_method` tinyint(1) unsigned NOT NULL DEFAULT 0 COMMENT '0 - Payment Company Account, 1 - Credit Card, 2 - E-Check',
  `coupon_number` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `discount_campaign_id` int(10) NOT NULL DEFAULT 0,
  `additional_info` text COLLATE utf8_unicode_ci NOT NULL,
  `cc_type` varchar(20) CHARACTER SET latin1 NOT NULL DEFAULT '',
  `cc_holder_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `cc_number` varchar(50) CHARACTER SET latin1 NOT NULL DEFAULT '',
  `cc_expires_month` varchar(2) CHARACTER SET latin1 NOT NULL DEFAULT '',
  `cc_expires_year` varchar(4) CHARACTER SET latin1 NOT NULL DEFAULT '',
  `cc_cvv_code` varchar(4) CHARACTER SET latin1 NOT NULL DEFAULT '',
  `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0 - preparing, 1 - pending, 2 - completed, 3 - refunded',
  `status_changed` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `email_sent` tinyint(1) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `payment_type` (`payment_method`),
  KEY `status` (`status`),
  KEY `advertise_plan_id` (`advertise_plan_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3;

INSERT INTO `<DB_PREFIX>orders` (`id`, `order_number`, `order_description`, `order_price`, `listing_id`, `vat_percent`, `vat_fee`, `total_price`, `currency`, `advertise_plan_id`, `customer_id`, `transaction_number`, `created_date`, `payment_date`, `payment_id`, `payment_method`, `coupon_number`, `discount_campaign_id`, `additional_info`, `cc_type`, `cc_holder_name`, `cc_number`, `cc_expires_month`, `cc_expires_year`, `cc_cvv_code`, `status`, `status_changed`, `email_sent`) SELECT
1, '7ncjwdlux35nqj2', 'Listings Purchasing', 20.00, 1, 0.00, 0.00, 20.00, id, 1, 1, '', NOW() - INTERVAL 1 DAY, NOW() - INTERVAL 1 HOUR, 1, 2, '', 0, '', '', '', '', '', '', '', 2, NOW() - INTERVAL 1 HOUR, 0 FROM `<DB_PREFIX>currencies` m WHERE is_default;
INSERT INTO `<DB_PREFIX>orders` (`id`, `order_number`, `order_description`, `order_price`, `listing_id`, `vat_percent`, `vat_fee`, `total_price`, `currency`, `advertise_plan_id`, `customer_id`, `transaction_number`, `created_date`, `payment_date`, `payment_id`, `payment_method`, `coupon_number`, `discount_campaign_id`, `additional_info`, `cc_type`, `cc_holder_name`, `cc_number`, `cc_expires_month`, `cc_expires_year`, `cc_cvv_code`, `status`, `status_changed`, `email_sent`) SELECT
2, '2cnrou9ozmum3jb', 'Order Descriptions', 10.00, 2, 0.00, 0.00, 10.00, id, 2, 2, '', NOW() - INTERVAL 5 DAY, NOW() - INTERVAL 1 DAY, 2, 1, '', 0, '', '', '', '', '', '', '', 2, NOW() - 1 DAY, 0 FROM `<DB_PREFIX>currencies` m WHERE is_default;
INSERT INTO `<DB_PREFIX>orders` (`id`, `order_number`, `order_description`, `order_price`, `listing_id`, `vat_percent`, `vat_fee`, `total_price`, `currency`, `advertise_plan_id`, `customer_id`, `transaction_number`, `created_date`, `payment_date`, `payment_id`, `payment_method`, `coupon_number`, `discount_campaign_id`, `additional_info`, `cc_type`, `cc_holder_name`, `cc_number`, `cc_expires_month`, `cc_expires_year`, `cc_cvv_code`, `status`, `status_changed`, `email_sent`) SELECT
3, '3fgieul6pkd6jp4', 'Order Descriptions', 10.00, 3, 0.00, 0.00, 10.00, id, 3, 1, '', NOW() - INTERVAL 6 DAY, NOW() - INTERVAL 3 DAY, 1, 1, '', 0, '', '', '', '', '', '', '', 2, NOW() - 3 DAY, 0 FROM `<DB_PREFIX>currencies` m WHERE is_default;


DROP TABLE IF EXISTS `<DB_PREFIX>site_info_frontend`;
CREATE TABLE IF NOT EXISTS `<DB_PREFIX>site_info_frontend` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `logo` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `favicon` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `email` varchar(70) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `email_visible` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '0 - hidden, 1 - show',
  `phone` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `phone_visible` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '0 - hidden, 1 - show',
  `custom_text_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `custom_text_description` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2;

INSERT INTO `<DB_PREFIX>site_info_frontend` (`id`, `logo`, `favicon`, `email`, `email_visible`, `phone`, `phone_visible`, `custom_text_name`, `custom_text_description`) VALUES
(1, '', '', 'directory@mail.com', 1, '+044 802 52578', 1, 'Custom text', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce ac augue neque. Nam pulvinar porta nisl eu dictum. Nulla sed eros viverra, facilisis tellus ut, varius dui. Phasellus at molestie eros.');


DROP TABLE IF EXISTS `<DB_PREFIX>social_networks`;
CREATE TABLE IF NOT EXISTS `<DB_PREFIX>social_networks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `site_id` int(11) NOT NULL DEFAULT '0',
  `icon` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `link` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `sort_order` smallint(1) unsigned NOT NULL DEFAULT '0',
  `is_published` tinyint(1) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=9;

INSERT INTO `<DB_PREFIX>social_networks` (`id`, `site_id`, `icon`, `name`, `link`, `sort_order`, `is_published`) VALUES
(1, 1, 'facebook.png', 'Facebook', 'http://facebook.com/#', 1, 1),
(2, 1, 'youtube.png', 'YouTube', 'http://youtube.com/#', 3, 1),
(3, 1, 'twitter.png', 'Twitter', 'http://twitter.com/#', 2, 1),
(4, 1, 'skype.png', 'Skype', 'http://web.skype.com/#', 4, 0),
(5, 1, 'pinterest.png', 'Pinterest', 'http://pinterest.com/#', 5, 0),
(6, 1, 'linkedin.png', 'LinkedIn', 'http://linkedin.com/#', 6, 0),
(7, 1, 'instagram.png', 'Instagram', 'http://instagram.com/#', 7, 0),
(8, 1, 'google_plus.png', 'Google+', 'https://plus.google.com/#', 8, 1);


DROP TABLE IF EXISTS `<DB_PREFIX>social_networks_login`;
CREATE TABLE IF NOT EXISTS `<DB_PREFIX>social_networks_login` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `type` varchar(32) CHARACTER SET latin1 NOT NULL DEFAULT '',
  `button_image` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `application_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `application_secret` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `sort_order` smallint(1) unsigned NOT NULL DEFAULT '0',
  `is_active` tinyint(1) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4;

INSERT INTO `<DB_PREFIX>social_networks_login` (`id`, `name`, `type`, `button_image`, `application_id`, `application_secret`, `sort_order`, `is_active`) VALUES
(1, 'Facebook', 'facebook', 'facebook.png', '', '', 1, 0),
(2, 'Google+', 'google', 'google.png', '', '', 2, 0),
(3, 'Twitter', 'twitter', 'twitter.png', '', '', 3, 0);


DROP TABLE IF EXISTS `<DB_PREFIX>inquiries`;
CREATE TABLE IF NOT EXISTS `<DB_PREFIX>inquiries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `inquiry_type` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0 - standard, 1 - direct',
  `category_id` int(10) unsigned NOT NULL DEFAULT '0',
  `listing_id` int(10) unsigned NOT NULL DEFAULT '0',
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `email` varchar(70) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `phone` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `region_id` int(10) unsigned NOT NULL DEFAULT '0',
  `subregion_id` int(10) unsigned NOT NULL DEFAULT '0',
  `availability` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `preferred_contact` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `date_created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `is_active` tinyint(1) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `category_id` (`category_id`),
  KEY `region_id` (`region_id`, `subregion_id`),
  KEY `listing_id` (`listing_id`),
  KEY `date_created` (`date_created`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2;

INSERT INTO `<DB_PREFIX>inquiries` (`id`, `inquiry_type`, `category_id`, `listing_id`, `name`, `email`, `phone`, `region_id`, `subregion_id`, `availability`, `preferred_contact`, `description`, `date_created`, `is_active`) VALUES
(1, 0, 1, 3, 'test', 'exampel@exampel.com', '123454321', 1, 13, 0, 0, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce ac augue neque. Nam pulvinar porta nisl eu dictum. Nulla sed eros viverra, facilisis tellus ut, varius dui. Phasellus at molestie eros. Interdum et malesuada fames ac ante ipsum primis in faucibus. Vivamus lobortis, ex ac finibus luctus, orci ex interdum ex, eget commodo ligula tortor vel quam. Morbi vel mi ligula. Integer id nunc sit amet augue euismod iaculis a et arcu.', NOW(), 1);

DROP TABLE IF EXISTS `<DB_PREFIX>reviews`;
CREATE TABLE IF NOT EXISTS `<DB_PREFIX>reviews` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `listing_id` int(10) unsigned NOT NULL DEFAULT '0',
  `customer_id` int(10) unsigned NOT NULL DEFAULT '0',
  `customer_email` varchar(100) CHARACTER SET latin1 NOT NULL,
  `customer_name` varchar(65) COLLATE utf8_unicode_ci NOT NULL,
  `message` text COLLATE utf8_unicode_ci NOT NULL,
  `rating_price` tinyint(1) NOT NULL DEFAULT '0',
  `rating_location` tinyint(1) NOT NULL DEFAULT '0',
  `rating_staff` tinyint(1) NOT NULL DEFAULT '0',
  `rating_services` tinyint(1) NOT NULL DEFAULT '0',
  `rating_value` float NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `is_public` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `inquiry_id` (`listing_id`),
  KEY `customer_id` (`customer_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=15;

INSERT INTO `<DB_PREFIX>reviews` (`id`, `listing_id`, `customer_id`, `customer_email`, `customer_name`, `message`, `rating_price`, `rating_location`, `rating_staff`, `rating_services`, `rating_value`, `created_at`, `is_public`) VALUES
(1, 4, 0, 'test@test.net', 'Nick', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Eligendi non quis exercitationem culpa nesciunt nihil aut nostrum explicabo reprehenderit optio amet ab temporibus asperiores quasi cupiditate.', 5, 4, 4, 4, 4.25, NOW() - INTERVAL 2 MONTH, 1),
(2, 3, 0, 'test@test.net', 'Nick', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Eligendi non quis exercitationem culpa nesciunt nihil aut nostrum explicabo reprehenderit optio amet ab temporibus asperiores quasi cupiditate.', 5, 5, 4, 4, 4.5, NOW() - INTERVAL 2 DAY, 1),
(3, 5, 0, 'test@test.net', 'Nick', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Eligendi non quis exercitationem culpa nesciunt nihil aut nostrum explicabo reprehenderit optio amet ab temporibus asperiores quasi cupiditate.', 5, 4, 5, 5, 4.75, NOW() - INTERVAL 3 DAY, 1),
(4, 4, 0, 'test@test.net', 'Nick', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Eligendi non quis exercitationem culpa nesciunt nihil aut nostrum explicabo reprehenderit optio amet ab temporibus asperiores quasi cupiditate.', 5, 5, 5, 2, 4.25, NOW() - INTERVAL 1 MONTH, 0),
(5, 6, 0, 'test@test.net', 'Nick', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Eligendi non quis exercitationem culpa nesciunt nihil aut nostrum explicabo reprehenderit optio amet ab temporibus asperiores quasi cupiditate.', 5, 5, 4, 4, 4.5, NOW() - INTERVAL 5 DAY, 1),
(6, 4, 0, 'test@test.net', 'Nick', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Eligendi non quis exercitationem culpa nesciunt nihil aut nostrum explicabo reprehenderit optio amet ab temporibus asperiores quasi cupiditate.', 5, 4, 5, 5, 4.75, NOW() - INTERVAL 2 MONTH, 0),
(7, 7, 0, 'test@test.net', 'Nick', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Eligendi non quis exercitationem culpa nesciunt nihil aut nostrum explicabo reprehenderit optio amet ab temporibus asperiores quasi cupiditate.', 5, 5, 4, 5, 4.75, NOW() - INTERVAL 8 DAY, 1),
(8, 7, 0, 'test@test.net', 'Nick', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Eligendi non quis exercitationem culpa nesciunt nihil aut nostrum explicabo reprehenderit optio amet ab temporibus asperiores quasi cupiditate.', 5, 5, 5, 4, 4.75, NOW() - INTERVAL 4 MONTH, 1),
(9, 4, 0, 'test@test.net', 'Nick', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Eligendi non quis exercitationem culpa nesciunt nihil aut nostrum explicabo reprehenderit optio amet ab temporibus asperiores quasi cupiditate.', 5, 4, 5, 5, 4.75, NOW() - INTERVAL 1 MONTH, 1),
(10, 3, 0, 'test@test.net', 'Nick', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Eligendi non quis exercitationem culpa nesciunt nihil aut nostrum explicabo reprehenderit optio amet ab temporibus asperiores quasi cupiditate.', 5, 5, 4, 4, 4.5, NOW() - INTERVAL 9 DAY, 1),
(11, 3, 0, 'test@test.net', 'Nick', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Eligendi non quis exercitationem culpa nesciunt nihil aut nostrum explicabo reprehenderit optio amet ab temporibus asperiores quasi cupiditate.', 5, 4, 4, 4, 4.25, NOW() - INTERVAL 5 MONTH, 0),
(12, 4, 0, 'test@test.net', 'Nick', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Eligendi non quis exercitationem culpa nesciunt nihil aut nostrum explicabo reprehenderit optio amet ab temporibus asperiores quasi cupiditate.', 5, 5, 2, 4, 4, NOW() - INTERVAL 10 MONTH, 1),
(13, 4, 0, 'test@test.net', 'Nick', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Eligendi non quis exercitationem culpa nesciunt nihil aut nostrum explicabo reprehenderit optio amet ab temporibus asperiores quasi cupiditate.', 5, 4, 4, 5, 4.5, NOW() - INTERVAL 2 DAY, 1),
(14, 5, 0, 'test@test.net', 'Nick', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Eligendi non quis exercitationem culpa nesciunt nihil aut nostrum explicabo reprehenderit optio amet ab temporibus asperiores quasi cupiditate.', 5, 4, 4, 5, 4.5, NOW() - INTERVAL 1 DAY, 1);

<?php

return array(
    // module classes
    'classes' => array(
        'DirectoryComponent',
        'Categories',
        'CustomerGroups',
        'Customers',
        'Inquiries',
        'Listings',
        'ListingsCategories',
        'Regions',
        'Reviews',
        'Orders',
        'Plans',
        'Home',
        'SiteInfoFrontend',
        'SocialNetworks',
        'SocialNetworksLogin',
        'AjaxHandler',
    ),
    // management links
    'managementLinks' => array(
        A::t('directory', 'Site Info') => 'siteInfoFrontend/siteinfo',
        A::t('directory', 'Categories') => 'categories/manage',
        A::t('directory', 'Locations') => 'regions/manage',
        A::t('directory', 'Listings') => 'listings/manage',
        A::t('directory', 'Accounts') => 'customers/manage',
        A::t('directory', 'Reviews') => 'reviews/manage',
        A::t('directory', 'Inquiries') => 'inquiries/manage',
        A::t('directory', 'Advertise Plans') => 'plans/manage',
        A::t('directory', 'Orders') => 'orders/manage',
    ),

    // Write here your Google API key
	'googleApiKey' => '',
);

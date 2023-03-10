<?php

return array(
    // module components
    'components' => array(
        'DirectoryComponent' => array('enable' => true, 'class' => 'DirectoryComponent'),
    ),

    // url manager (optional)
    //'urlManager' => array(
    //    'rules' => array(
    //        'page/([0-9]+)/(.*?)' => 'page/view/id/{$0}',
    //    ),
    //),

	// Default Backend url (optional, if defined - will be used as application default settings)
	'backendDefaultUrl' => 'siteInfoFrontend/siteinfo',

    // Default settings (optional, if defined - will be used as application default settings)
	//'defaultErrorController' => 'Error',
    'defaultController' => 'Home',
    'defaultAction' => 'index',
);

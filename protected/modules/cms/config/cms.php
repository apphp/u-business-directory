<?php

return array(
    // url manager
    'urlManager' => array(
        'rules' => array(
            'pages/view/id/([0-9]+)' => 'pages/view/id/{$0}',
            'pages/view/id/([0-9]+)/(.*?)' => 'pages/view/id/{$0}',
            'pages/view/([0-9]+)' => 'pages/view/id/{$0}',
            'pages/view/([0-9]+)/(.*?)' => 'pages/view/id/{$0}',
            'pages/([0-9]+)' => 'pages/view/id/{$0}',
            'pages/([0-9]+)/(.*?)' => 'pages/view/id/{$0}',
        ),
    ),    
    
	'defaultController' => 'pages',
    'defaultAction' => 'view',
);

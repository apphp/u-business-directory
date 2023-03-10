<?php

return array(
    // module components
    'components' => array(
        'NewsComponent'       => array('enable' => true, 'class' => 'NewsComponent'),
    ),

    // url manager
    'urlManager' => array(
        'rules' => array(
            'news/view/id/([0-9]+)' => 'news/view/id/{$0}',
            'news/view/id/([0-9]+)/(.*?)' => 'news/view/id/{$0}',
            'news/view/([0-9]+)' => 'news/view/id/{$0}',
            'news/view/([0-9]+)/(.*?)' => 'news/view/id/{$0}',
            'news/([0-9]+)' => 'news/view/id/{$0}',
            'news/([0-9]+)/(.*?)' => 'news/view/id/{$0}',
        ),
    ),    
);

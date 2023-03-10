<?php
return array(
    // module classes
    'classes' => array(
        'NewsComponent',
        'News',
        'NewsSubscribers',
    ),
    // management links
    'managementLinks' => array(
        A::t('news', 'News')       => 'news/manage',
        A::t('news', 'Subscribers') => 'newsSubscribers/manage'
    ),    
);

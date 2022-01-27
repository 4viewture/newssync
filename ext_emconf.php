<?php

/***************************************************************
 * Extension Manager/Repository config file for ext: "newssync"
 *
 * Auto generated by Extension Builder 2016-12-09
 *
 * Manual updates:
 * Only the data in the array - anything else is removed by next write.
 * "version" and "dependencies" must not be touched!
 ***************************************************************/

$EM_CONF[$_EXTKEY] = array(
    'title' => 'Newssync',
    'description' => 'Sync News',
    'category' => 'plugin',
    'author' => 'Kay Strobach',
    'author_email' => 'typo3@kay-strobach.de',
    'state' => 'alpha',
    'internal' => '',
    'uploadfolder' => '0',
    'createDirs' => '',
    'clearCacheOnLoad' => 0,
    'version' => '9.11.0',
    'constraints' => array(
        'depends' => array(
            'typo3' => '9.5.0-9.5.99',
            'news' => '7.3.0-7.3.99',
        ),
        'conflicts' => array(
        ),
        'suggests' => array(
        ),
    ),
    'autoload' => array(
        'psr-4' => array(
            'Fourviewture\\Newssync\\' => 'Classes/'
        )
    )
);

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

$EM_CONF['newssync'] = array(
    'title' => 'Newssync',
    'description' => 'Sync News',
    'category' => 'plugin',
    'author' => 'Kay Strobach',
    'author_email' => 'typo3@kay-strobach.de',
    'state' => 'alpha',
    'version' => '10.0.7',
    'constraints' => array(
        'depends' => array(
            'typo3' => '10.4.0-10.5.99',
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

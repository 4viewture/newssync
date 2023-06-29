<?php

/**
 * Definitions for modules provided by EXT:examples
 */
return [
    'newssync' => [
        'parent' => 'site',
        'position' => ['after' => 'site_configuration'],
        'access' => 'user,group',
        'workspaces' => 'live',
        'path' => '/module/newssync',
        'labels' => 'LLL:EXT:newssync/Resources/Private/Language/locallang_sync.xlf',
        'extensionName' => 'Newssync',
        'icon' => 'EXT:newssync/Resources/Public/Icons/module_syncconfiguration.svg',
        'controllerActions' => [
            \Fourviewture\Newssync\Controller\SyncConfigurationController::class => [
                'list','show','refreshData',
            ],
        ],
    ]
];

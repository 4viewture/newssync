<?php
return [
    'ctrl' => [
        'title' => 'LLL:EXT:newssync/Resources/Private/Language/locallang_db.xlf:tx_newssync_domain_model_syncconfiguration',
        'label' => 'title',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'versioningWS' => \true,
        'languageField' => 'sys_language_uid',
        'transOrigPointerField' => 'l10n_parent',
        'transOrigDiffSourceField' => 'l10n_diffsource',
        'delete' => 'deleted',
        'enablecolumns' => ['disabled' => 'hidden', 'starttime' => 'starttime', 'endtime' => 'endtime'],
        'searchFields' => 'title,uri,description,processingfolder,lastsync,lastsynclog,auto_clear_cache_for_plugin,news_is_hidden_after_import,news_type,news_is_top_news,storage_pid,clear_cache_pages,',
        'iconfile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('newssync') . 'Resources/Public/Icons/tx_newssync_domain_model_syncconfiguration.gif'
    ],
    'types' => [
        '1' => [
            'showitem' => 'sys_language_uid,--palette--,l10n_parent,l10n_diffsource,hidden,title,uri,processingfolder,storage_pid,description,--div--;News,news_type,news_is_hidden_after_import,news_is_top_news,categories,--div--;Cache,auto_clear_cache_for_plugin,clear_cache_pages,--div--;Sync Log,lastsync,lastsynclog,--div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.access,starttime,endtime'
        ]
    ],
    'palettes' => ['1' => ['showitem' => 'processingfolder,']],
    'columns' => [
        'sys_language_uid' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.language',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'sys_language',
                'foreign_table_where' => 'ORDER BY sys_language.title',
                'items' => [
                    ['LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.allLanguages', -1],
                    ['LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.default_value', 0]
                ]
            ]
        ],
        'l10n_parent' => [
            'displayCond' => 'FIELD:sys_language_uid:>:0',
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.l18n_parent',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [['', 0]],
                'foreign_table' => 'tx_newssync_domain_model_syncconfiguration',
                'foreign_table_where' => 'AND tx_newssync_domain_model_syncconfiguration.pid=###CURRENT_PID### AND tx_newssync_domain_model_syncconfiguration.sys_language_uid IN (-1,0)'
            ]
        ],
        'l10n_diffsource' => ['config' => ['type' => 'passthrough']],
        't3ver_label' => [
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.versionLabel',
            'config' => ['type' => 'input', 'size' => 30, 'max' => 255]
        ],
        'hidden' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.hidden',
            'config' => ['type' => 'check']
        ],
        'starttime' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.starttime',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'size' => 13,
                'eval' => 'datetime',
                'checkbox' => 0,
                'default' => 0,
                'range' => ['lower' => \mktime(0, 0, 0, \date('m'), \date('d'), \date('Y'))],
                'behaviour' => ['allowLanguageSynchronization' => \true]
            ]
        ],
        'endtime' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.endtime',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'size' => 13,
                'eval' => 'datetime',
                'checkbox' => 0,
                'default' => 0,
                'range' => ['lower' => \mktime(0, 0, 0, \date('m'), \date('d'), \date('Y'))],
                'behaviour' => ['allowLanguageSynchronization' => \true]
            ]
        ],
        'title' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:newssync/Resources/Private/Language/locallang_db.xlf:tx_newssync_domain_model_syncconfiguration.title',
            'config' => ['type' => 'input', 'size' => 30, 'eval' => 'trim']
        ],
        'uri' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:newssync/Resources/Private/Language/locallang_db.xlf:tx_newssync_domain_model_syncconfiguration.uri',
            'config' => ['type' => 'input', 'size' => 30, 'eval' => 'trim']
        ],
        'description' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:newssync/Resources/Private/Language/locallang_db.xlf:tx_newssync_domain_model_syncconfiguration.description',
            'config' => ['type' => 'text', 'cols' => 40, 'rows' => 15, 'eval' => 'trim']
        ],
        'processingfolder' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:newssync/Resources/Private/Language/locallang_db.xlf:tx_newssync_domain_model_syncconfiguration.processingfolder',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
                'placeholder' => 'LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:sys_file_storage.processingfolder.placeholder'
            ]
        ],
        'lastsync' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:newssync/Resources/Private/Language/locallang_db.xlf:tx_newssync_domain_model_syncconfiguration.lastsync',
            'config' => [
                'dbType' => 'datetime',
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'size' => 12,
                'eval' => 'datetime',
                'checkbox' => 0,
                'default' => '0000-00-00 00:00:00'
            ]
        ],
        'lastsynclog' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:newssync/Resources/Private/Language/locallang_db.xlf:tx_newssync_domain_model_syncconfiguration.lastsynclog',
            'config' => ['type' => 'text', 'cols' => 40, 'rows' => 15, 'eval' => 'trim']
        ],
        'auto_clear_cache_for_plugin' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:newssync/Resources/Private/Language/locallang_db.xlf:tx_newssync_domain_model_syncconfiguration.auto_clear_cache_for_plugin',
            'config' => ['type' => 'check', 'default' => 0]
        ],
        'news_is_hidden_after_import' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:newssync/Resources/Private/Language/locallang_db.xlf:tx_newssync_domain_model_syncconfiguration.news_is_hidden_after_import',
            'config' => ['type' => 'check', 'default' => 0]
        ],
        'news_is_top_news' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:newssync/Resources/Private/Language/locallang_db.xlf:tx_newssync_domain_model_syncconfiguration.news_is_top_news',
            'config' => ['type' => 'check', 'default' => 0]
        ],
        'storage_pid' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:newssync/Resources/Private/Language/locallang_db.xlf:tx_newssync_domain_model_syncconfiguration.storage_pid',
            'config' => [
                'allowed' => 'pages',
                'behaviour' => [
                    'allowLanguageSynchronization' => 1,
                ],
                'default' => 0,
                'internal_type' => 'db',
                'maxitems' => 1,
                'minitems' => 1,
                'size' => 1,
                'suggestOptions' => [
                    'default' => [
                        'additionalSearchFields' => 'nav_title, url',
                        'addWhere' => 'AND pages.uid != ###THIS_UID###'
                    ]
                ],
                'type' => 'group'
            ]
        ],
        'clear_cache_pages' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:newssync/Resources/Private/Language/locallang_db.xlf:tx_newssync_domain_model_syncconfiguration.clear_cache_pages',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'foreign_table' => 'pages',
                'MM' => 'tx_newssync_syncconfiguration_clearcachepages_page_mm',
                'size' => 10,
                'autoSizeMax' => 30,
                'maxitems' => 9999,
                'multiple' => 0,
                'fieldControl' => [
                    'addRecord' => [
                        'options' => [
                            'pid' => '###CURRENT_PID###',
                            'setValue' => 'prepend',
                            'table' => 'pages',
                            'title' => 'Create new'
                        ]
                    ],
                    'editPopup' => [
                        'title' => 'Edit',
                        'windowOpenParameters' => 'height=350,width=580,status=0,menubar=0,scrollbars=1'
                    ]
                ]
            ]
        ],
        'categories' => [
            'config' => [
                'type' => 'category'
            ]
        ]
    ]
];

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_newssync_domain_model_syncconfiguration');

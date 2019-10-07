<?php
return array(
	'ctrl' => array(
		'title'	=> 'LLL:EXT:newssync/Resources/Private/Language/locallang_db.xlf:tx_newssync_domain_model_syncconfiguration',
		'label' => 'title',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		'dividers2tabs' => TRUE,
		'versioningWS' => 2,
		'versioning_followPages' => TRUE,

		'languageField' => 'sys_language_uid',
		'transOrigPointerField' => 'l10n_parent',
		'transOrigDiffSourceField' => 'l10n_diffsource',
		'delete' => 'deleted',
		'enablecolumns' => array(
			'disabled' => 'hidden',
			'starttime' => 'starttime',
			'endtime' => 'endtime',
		),
		'searchFields' => 'title,uri,description,processingfolder,lastsync,lastsynclog,auto_clear_cache_for_plugin,news_is_hidden_after_import,news_is_top_news,storage_pid,clear_cache_pages,',
		'iconfile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('newssync') . 'Resources/Public/Icons/tx_newssync_domain_model_syncconfiguration.gif'
	),
	'interface' => array(
		'showRecordFieldList' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden, title, uri, description, processingfolder, lastsync, lastsynclog, auto_clear_cache_for_plugin, news_is_hidden_after_import, news_is_top_news, storage_pid, clear_cache_pages',
	),
	'types' => array(
        '1' => array('showitem' => 'sys_language_uid;;;;1-1-1, l10n_parent, l10n_diffsource, hidden, title, uri, storage_pid, description, --div--;News, news_is_hidden_after_import, news_is_top_news, --div--;Cache,auto_clear_cache_for_plugin,clear_cache_pages, --div--;Sync Log,lastsync, lastsynclog, --div--;LLL:EXT:cms/locallang_ttc.xlf:tabs.access, starttime, endtime'),
	),
	'palettes' => array(
        '1' => array('showitem' => 'processingfolder,'),
	),
	'columns' => array(

		'sys_language_uid' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.language',
			'config' => array(
				'type' => 'select',
				'renderType' => 'selectSingle',
				'foreign_table' => 'sys_language',
				'foreign_table_where' => 'ORDER BY sys_language.title',
				'items' => array(
					array('LLL:EXT:lang/locallang_general.xlf:LGL.allLanguages', -1),
					array('LLL:EXT:lang/locallang_general.xlf:LGL.default_value', 0)
				),
			),
		),
		'l10n_parent' => array(
			'displayCond' => 'FIELD:sys_language_uid:>:0',
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.l18n_parent',
			'config' => array(
				'type' => 'select',
				'renderType' => 'selectSingle',
				'items' => array(
					array('', 0),
				),
				'foreign_table' => 'tx_newssync_domain_model_syncconfiguration',
				'foreign_table_where' => 'AND tx_newssync_domain_model_syncconfiguration.pid=###CURRENT_PID### AND tx_newssync_domain_model_syncconfiguration.sys_language_uid IN (-1,0)',
			),
		),
		'l10n_diffsource' => array(
			'config' => array(
				'type' => 'passthrough',
			),
		),

		't3ver_label' => array(
			'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.versionLabel',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'max' => 255,
			)
		),

		'hidden' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.hidden',
			'config' => array(
				'type' => 'check',
			),
		),
		'starttime' => array(
			'exclude' => 1,
			'l10n_mode' => 'mergeIfNotBlank',
			'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.starttime',
			'config' => array(
				'type' => 'input',
				'size' => 13,
				'max' => 20,
				'eval' => 'datetime',
				'checkbox' => 0,
				'default' => 0,
				'range' => array(
					'lower' => mktime(0, 0, 0, date('m'), date('d'), date('Y'))
				),
			),
		),
		'endtime' => array(
			'exclude' => 1,
			'l10n_mode' => 'mergeIfNotBlank',
			'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.endtime',
			'config' => array(
				'type' => 'input',
				'size' => 13,
				'max' => 20,
				'eval' => 'datetime',
				'checkbox' => 0,
				'default' => 0,
				'range' => array(
					'lower' => mktime(0, 0, 0, date('m'), date('d'), date('Y'))
				),
			),
		),

		'title' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:newssync/Resources/Private/Language/locallang_db.xlf:tx_newssync_domain_model_syncconfiguration.title',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
		),
		'uri' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:newssync/Resources/Private/Language/locallang_db.xlf:tx_newssync_domain_model_syncconfiguration.uri',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
		),
		'description' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:newssync/Resources/Private/Language/locallang_db.xlf:tx_newssync_domain_model_syncconfiguration.description',
			'config' => array(
				'type' => 'text',
				'cols' => 40,
				'rows' => 15,
				'eval' => 'trim'
			)
		),
		'processingfolder' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:newssync/Resources/Private/Language/locallang_db.xlf:tx_newssync_domain_model_syncconfiguration.processingfolder',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
		),
		'lastsync' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:newssync/Resources/Private/Language/locallang_db.xlf:tx_newssync_domain_model_syncconfiguration.lastsync',
			'config' => array(
				'dbType' => 'datetime',
				'type' => 'input',
				'size' => 12,
				'eval' => 'datetime',
				'checkbox' => 0,
				'default' => '0000-00-00 00:00:00'
			),
		),
		'lastsynclog' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:newssync/Resources/Private/Language/locallang_db.xlf:tx_newssync_domain_model_syncconfiguration.lastsynclog',
			'config' => array(
				'type' => 'text',
				'cols' => 40,
				'rows' => 15,
				'eval' => 'trim'
			)
		),
		'auto_clear_cache_for_plugin' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:newssync/Resources/Private/Language/locallang_db.xlf:tx_newssync_domain_model_syncconfiguration.auto_clear_cache_for_plugin',
			'config' => array(
				'type' => 'check',
				'default' => 0
			)
		),
		'news_is_hidden_after_import' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:newssync/Resources/Private/Language/locallang_db.xlf:tx_newssync_domain_model_syncconfiguration.news_is_hidden_after_import',
			'config' => array(
				'type' => 'check',
				'default' => 0
			)
		),
		'news_is_top_news' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:newssync/Resources/Private/Language/locallang_db.xlf:tx_newssync_domain_model_syncconfiguration.news_is_top_news',
			'config' => array(
				'type' => 'check',
				'default' => 0
			)
		),
		'storage_pid' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:newssync/Resources/Private/Language/locallang_db.xlf:tx_newssync_domain_model_syncconfiguration.storage_pid',
			'config' => array(
				'type' => 'select',
				'renderType' => 'selectSingle',
				'foreign_table' => 'pages',
				'minitems' => 0,
				'maxitems' => 1,
			),
		),
		'clear_cache_pages' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:newssync/Resources/Private/Language/locallang_db.xlf:tx_newssync_domain_model_syncconfiguration.clear_cache_pages',
			'config' => array(
				'type' => 'select',
				'renderType' => 'selectMultipleSideBySide',
				'foreign_table' => 'pages',
				'MM' => 'tx_newssync_syncconfiguration_clearcachepages_page_mm',
				'size' => 10,
				'autoSizeMax' => 30,
				'maxitems' => 9999,
				'multiple' => 0,
				'wizards' => array(
					'_PADDING' => 1,
					'_VERTICAL' => 1,
					'edit' => array(
						'module' => array(
							'name' => 'wizard_edit',
						),
						'type' => 'popup',
						'title' => 'Edit',
						'icon' => 'edit2.gif',
						'popup_onlyOpenIfSelected' => 1,
						'JSopenParams' => 'height=350,width=580,status=0,menubar=0,scrollbars=1',
						),
					'add' => Array(
						'module' => array(
							'name' => 'wizard_add',
						),
						'type' => 'script',
						'title' => 'Create new',
						'icon' => 'add.gif',
						'params' => array(
							'table' => 'pages',
							'pid' => '###CURRENT_PID###',
							'setValue' => 'prepend'
						),
					),
				),
			),
		),

	),
);## EXTENSION BUILDER DEFAULTS END TOKEN - Everything BEFORE this line is overwritten with the defaults of the extension builder

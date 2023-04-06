<?php
if (!defined('TYPO3')) {
    die('Access denied.');
}

if (TYPO3_MODE === 'BE') {

    /**
     * Registers a Backend Module
     */
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
        'Newssync',
        'site',	 // Make module a submodule of 'tools'
        'sync',	// Submodule key
        '',						// Position
        array(
            \Fourviewture\Newssync\Controller\SyncConfigurationController::class => 'list, show, refreshData',

        ),
        array(
            'access' => 'user,group',
            'icon'   => 'EXT:newssync/Resources/Public/Icons/module_syncconfiguration.svg',
            'labels' => 'LLL:EXT:newssync/Resources/Private/Language/locallang_sync.xlf',
        )
    );
}

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_newssync_domain_model_syncconfiguration', 'EXT:newssync/Resources/Private/Language/locallang_csh_tx_newssync_domain_model_syncconfiguration.xlf');

## EXTENSION BUILDER DEFAULTS END TOKEN - Everything BEFORE this line is overwritten with the defaults of the extension builder

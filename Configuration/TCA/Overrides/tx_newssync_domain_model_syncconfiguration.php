<?php

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::makeCategorizable(
    'newssync',
    'tx_newssync_domain_model_syncconfiguration',
    // optional: in case the field would need a different name as "categories".
    // The field is mandatory for TCEmain to work internally.
    'categories',
    // optional: add TCA options which controls how the field is displayed. e.g position and name of the category tree.
    [
        'position' => 'after:news_is_top_news',
        'fieldList' => 'categories'
    ]
);

$GLOBALS['TCA']['tx_newssync_domain_model_syncconfiguration']['columns']['news_type'] = $GLOBALS['TCA']['tx_news_domain_model_news']['columns']['type'];

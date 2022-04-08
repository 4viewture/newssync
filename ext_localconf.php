<?php

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['newssync']['importservices'][]
    = \Fourviewture\Newssync\Services\Provider\RssImportService::class;
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['newssync']['importservices'][]
    = \Fourviewture\Newssync\Services\Provider\JsonImportService::class;
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['clearCachePostProc']['newssync']
    = \Fourviewture\Newssync\Hooks\T3libTcemainHook::class . '->clearCachePostProc';

if (TYPO3_MODE === 'BE') {
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['extbase']['commandControllers'][]
        = \Fourviewture\Newssync\Command\NewssyncCommandController::class;
}

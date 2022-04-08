<?php

namespace Fourviewture\Newssync\Hooks;

use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\DataHandling\DataHandler;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class T3libTcemainHook
{
    const CACHE_DIRECTORY = 'typo3temp/Cache/Data/newssync';

    /**
     * Deletes newssync folders inside typo3temp/.
     *
     * @param array                                    $params
     * @param DataHandler $pObj
     */
    public function clearCachePostProc(array $params, DataHandler &$pObj)
    {
        GeneralUtility::rmdir(
            Environment::getPublicPath() . '/' . self::CACHE_DIRECTORY,
            true
        );
    }
}

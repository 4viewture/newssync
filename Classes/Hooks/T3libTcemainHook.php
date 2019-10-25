<?php

namespace Fourviewture\Newssync\Hooks;

use TYPO3\CMS\Core\DataHandling\DataHandler;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class T3libTcemainHook
{
    const CACHE_DIRECTORY = PATH_site . 'typo3temp/Cache/Data/newssync';

    /**
     * Deletes newssync folders inside typo3temp/.
     *
     * @param array                                    $params
     * @param \TYPO3\CMS\Core\DataHandling\DataHandler $pObj
     */
    public function clearCachePostProc(array $params, DataHandler &$pObj)
    {
        GeneralUtility::rmdir(
            self::CACHE_DIRECTORY,
            true
        );
    }
}

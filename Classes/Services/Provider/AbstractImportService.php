<?php

namespace Fourviewture\Newssync\Services\Provider;


use Fourviewture\Newssync\Domain\Model\SyncConfiguration;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Extbase\Service\CacheService;

class AbstractImportService
{
    /**
     * @var array
     */
    protected $output = array();

    /**
     * @param SyncConfiguration $syncConfiguration
     * @return boolean
     */
    public function canHandle(SyncConfiguration $syncConfiguration) {
        return false;
    }

    /**
     * @param SyncConfiguration $syncConfiguration
     */
    public function handle(SyncConfiguration $syncConfiguration) {
        $this->output = array();
        $this->log('Importing with ' . get_class($this));
    }

    /**
     * @param $message
     */
    public function log($message) {
        $this->output[] = $message;
    }

    /**
     * @return string
     */
    public function getLog() {
        return implode(chr(10), $this->output);
    }

    /**
     * @param int $uid of storage folder
     */
    protected function clearCache($uid)
    {
        $pagets = \TYPO3\CMS\Backend\Utility\BackendUtility::getPagesTSconfig($uid);
        if (isset($pagets['TCEMAIN.']['clearCacheCmd'])) {
            $this->log('    found TCEMAIN.clearCacheCmd=' . $pagets['TCEMAIN.']['clearCacheCmd']);
            /** @var CacheService $cacheService */
            $cacheService = $this->getObjectManager()->get(CacheService::class);
            $cacheService->clearPageCache(GeneralUtility::trimExplode(',', $pagets['TCEMAIN.']['clearCacheCmd']));
        } else {
            $this->log('    found no TCEMAIN.clearCacheCmd, so cache is not cleared');
        }
    }

    /**
     * @return ObjectManager
     */
    protected function getObjectManager()
    {
        return GeneralUtility::makeInstance(ObjectManager::class);
    }
}

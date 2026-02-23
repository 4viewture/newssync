<?php

namespace Fourviewture\Newssync\Services;

use Fourviewture\Newssync\Domain\Model\SyncConfiguration;
use Fourviewture\Newssync\Services\Exception\SyncException;
use Fourviewture\Newssync\Services\Provider\AbstractImportService;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Resource\StorageRepository;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;

/**
 * Class ImportService
 * @package Fourviewture\Newssync\Services
 * @singleton
 */
class ImportService
{
    /**
     * @var ObjectManager
     */
    protected $objectManager = null;

    /**
     * @var array
     */
    protected array $services = array();



    /**
     * ImportService constructor.
     */
    public function __construct()
    {
        if (is_array($GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['newssync']['importservices'])) {
            foreach ($GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['newssync']['importservices'] as $service) {
                $this->services[] = GeneralUtility::makeInstanceForDi(
                    $service,
                    GeneralUtility::makeInstance(\GeorgRinger\News\Domain\Repository\NewsRepository::class),
                    GeneralUtility::makeInstance(PersistenceManager::class),
                    GeneralUtility::makeInstance(ConnectionPool::class),
                    GeneralUtility::makeInstance(StorageRepository::class)
                );
            }
        }


    }

    public function import(SyncConfiguration &$syncConfiguration): void
    {
        if (count($this->services) === 0) {
            $syncConfiguration->setLastsynclog('No Importservices defined (' . count($this->services) . ')');
        }

        $syncConfiguration->setLastsync(new \DateTime('now'));
        /** @var AbstractImportService $service */
        $handled = false;
        try {
            $service = $this->getMatchingService($syncConfiguration);
            $service->handle($syncConfiguration);
            $syncConfiguration->setLastsynclog($service->getLog());
        } catch (SyncException $e) {
            $syncConfiguration->setLastsynclog($e->getMessage());
        }

        if (!$handled) {
            $syncConfiguration->setLastsynclog('No Matching service found');
        }
    }

    protected function getMatchingService(SyncConfiguration $syncConfiguration): AbstractImportService
    {
        foreach ($this->services as $service) {
            if ($service->canHandle($syncConfiguration)) {
                return $service;
            }
        }

        throw new SyncException('No Matching service found');
    }
}

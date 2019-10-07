<?php

namespace Fourviewture\Newssync\Services;

use Fourviewture\Newssync\Domain\Model\SyncConfiguration;
use Fourviewture\Newssync\Services\Exception\SyncException;
use Fourviewture\Newssync\Services\Provider\AbstractImportService;
use TYPO3\CMS\Extbase\Object\ObjectManager;

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
    protected $services = array();

    /**
     * ImportService constructor.
     * @param ObjectManager $objectManager
     */
    public function __construct(ObjectManager $objectManager)
    {
        $this->objectManager = $objectManager;

        if (is_array($GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['newssync']['importservices'])) {
            foreach ($GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['newssync']['importservices'] as $service) {
                $this->services[] = $this->objectManager->get($service);
            }
        }
    }

    public function import(SyncConfiguration &$syncConfiguration) {
        if (count($this->services) === 0) {
            $syncConfiguration->setLastsynclog('No Importservices defined (' . count($this->services) . ')');
        }

        $syncConfiguration->setLastsync(new \DateTime('now'));
        /** @var AbstractImportService $service */
        $handled = false;
        foreach($this->services as $service) {
            try {
                if ($service->canHandle($syncConfiguration)) {
                    $service->handle($syncConfiguration);
                    $syncConfiguration->setLastsynclog($service->getLog());
                    $handled = true;
                    break;
                }
            } catch (SyncException $exception) {
                $syncConfiguration->setLastsynclog($exception->getMessage());
            }
        }
        if (!$handled) {
            $syncConfiguration->setLastsynclog('No Matching service found');
        }
    }

}

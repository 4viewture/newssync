<?php

namespace Fourviewture\Newssync\Command;

use Fourviewture\Newssync\Domain\Repository\SyncConfigurationRepository;
use Fourviewture\Newssync\Services\ImportService;
use Fourviewture\Newssync\Domain\Model\SyncConfiguration;
use TYPO3\CMS\Extbase\Mvc\Controller\CommandController;
use TYPO3\CMS\Extbase\Annotation as Extbase;

class NewssyncCommandController extends CommandController
{
    /**
     * syncConfigurationRepository
     *
     * @var SyncConfigurationRepository
     * @Extbase\Inject
     */
    protected $syncConfigurationRepository = null;
    /**
     * @var ImportService
     * @Extbase\Inject
     */
    protected $importService;
    /**
     *
     */
    public function syncAllCommand()
    {
        $syncConfigurations = $this->syncConfigurationRepository->findAll();
        /** @var SyncConfiguration $syncConfiguration */
        foreach ($syncConfigurations as $syncConfiguration) {
            $this->outputLine('--------------------------------------------------------------------------------');
            $this->outputLine('Starting: ' . $syncConfiguration->getTitle());
            $this->importService->import($syncConfiguration);
            $this->syncConfigurationRepository->update($syncConfiguration);
            $this->outputLine($syncConfiguration->getLastsynclog());
        }
    }
}

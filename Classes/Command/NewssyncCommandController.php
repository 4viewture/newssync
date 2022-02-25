<?php

namespace Fourviewture\Newssync\Command;

use Fourviewture\Newssync\Domain\Model\SyncConfiguration;
use TYPO3\CMS\Extbase\Mvc\Controller\CommandController;

class NewssyncCommandController extends CommandController
{
    /**
     * syncConfigurationRepository
     *
     * @var \Fourviewture\Newssync\Domain\Repository\SyncConfigurationRepository
     * @inject
     */
    protected $syncConfigurationRepository = null;
    /**
     * @var \Fourviewture\Newssync\Services\ImportService
     * @inject
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

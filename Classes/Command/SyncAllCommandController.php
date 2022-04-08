<?php

namespace Fourviewture\Newssync\Command;

use Fourviewture\Newssync\Domain\Repository\SyncConfigurationRepository;
use Fourviewture\Newssync\Services\ImportService;
use Fourviewture\Newssync\Domain\Model\SyncConfiguration;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class SyncAllCommandController extends Command
{
    /**
     * syncConfigurationRepository
     *
     * @var SyncConfigurationRepository
     */
    protected $syncConfigurationRepository = null;

    /**
     * @var ImportService
     */
    protected $importService;

    public function injectSyncConfigurationRepository(SyncConfigurationRepository $syncConfigurationRepository): void
    {
        $this->syncConfigurationRepository = $syncConfigurationRepository;
    }

    public function injectImportService(ImportService $importService): void
    {
        $this->importService = $importService;
    }

    /**
     * Configure the command by defining the name, options and arguments
     */
    protected function configure()
    {
        $this->setDescription('Sync all newssync entries.');
    }

    /**
     *
     */
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $syncConfigurations = $this->syncConfigurationRepository->findAll();
        /** @var SyncConfiguration $syncConfiguration */
        foreach ($syncConfigurations as $syncConfiguration) {
            $output->writeln('--------------------------------------------------------------------------------');
            $output->writeln('Starting: ' . $syncConfiguration->getTitle());
            $this->importService->import($syncConfiguration);
            $this->syncConfigurationRepository->update($syncConfiguration);
            $output->writeln($syncConfiguration->getLastsynclog());
        }

        return 0;
    }
}

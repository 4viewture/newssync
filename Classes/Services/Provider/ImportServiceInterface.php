<?php

namespace Fourviewture\Newssync\Services\Provider;

use Fourviewture\Newssync\Domain\Model\SyncConfiguration;
use GeorgRinger\News\Domain\Repository\NewsRepository;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Resource\StorageRepository;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;

interface ImportServiceInterface
{
    public function __construct(
        NewsRepository $newsRepository,
        PersistenceManager $persistenceManager,
        ?ConnectionPool $connectionPool = null,
        ?StorageRepository $storageRepository = null
    );

    public function canHandle(SyncConfiguration $syncConfiguration): bool;

    public function handle(SyncConfiguration $syncConfiguration): void;

    public function getLog(): string;

    public function getLabelForTca(): string;
}

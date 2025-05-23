<?php

namespace Fourviewture\Newssync\Services\Provider;

use Fourviewture\Newssync\Domain\Model\SyncConfiguration;
use GeorgRinger\News\Domain\Model\FileReference;
use GeorgRinger\News\Domain\Model\Link;
use GeorgRinger\News\Domain\Model\News;
use GeorgRinger\News\Domain\Model\NewsDefault;
use GeorgRinger\News\Domain\Repository\NewsRepository;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\Restriction\HiddenRestriction;
use TYPO3\CMS\Core\DataHandling\Model\RecordStateFactory;
use TYPO3\CMS\Core\DataHandling\SlugHelper;
use TYPO3\CMS\Core\Resource\Exception\ExistingTargetFileNameException;
use TYPO3\CMS\Core\Resource\Folder;
use TYPO3\CMS\Core\Resource\Index\FileIndexRepository;
use TYPO3\CMS\Core\Resource\ResourceFactory;
use TYPO3\CMS\Core\Resource\StorageRepository;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;
use TYPO3\CMS\Extbase\Service\CacheService;

class AbstractImportService
{
    public const IMPORT_ID = 'newssync_unknown';
    public const PRIORITY = 0;

    /**
     * @var array
     */
    protected $output = array();

    /**
     * @var PersistenceManager
     */
    protected $persistenceManager;

    /**
     * @var NewsRepository
     */
    protected $newsRepository = null;

    /**
     * @var mixed
     */
    protected $emConfiguration = null;

    /**
     * @var ConnectionPool
     */
    protected $connectionPool;

    /**
     * @var StorageRepository
     */
    protected $storageRepository;

    public function __construct(
        NewsRepository $newsRepository,
        PersistenceManager $persistenceManager,
        ConnectionPool $connectionPool = null,
        StorageRepository $storageRepository = null
    )
    {
        $this->newsRepository = $newsRepository;
        $this->persistenceManager = $persistenceManager;
        if ($connectionPool === null) {
            $connectionPool = GeneralUtility::makeInstance(ConnectionPool::class);
        }
        if ($storageRepository === null) {
            $this->storageRepository = GeneralUtility::makeInstance(StorageRepository::class);
        }
        $this->connectionPool = $connectionPool;
        /** @var ExtensionConfiguration $configurationUtility */
        $configurationUtility = GeneralUtility::makeInstance(ExtensionConfiguration::class);
        $this->emConfiguration = $configurationUtility->get('newssync');
    }


    /**
     * @param SyncConfiguration $syncConfiguration
     * @return boolean
     */
    public function canHandle(SyncConfiguration $syncConfiguration)
    {
        return false;
    }
    /**
     * @param SyncConfiguration $syncConfiguration
     */
    public function handle(SyncConfiguration $syncConfiguration)
    {
        $this->output = array();
        $this->log('Importing with ' . get_class($this));
    }
    /**
     * @param $message
     */
    public function log($message)
    {
        $this->output[] = $message;
    }
    /**
     * @return string
     */
    public function getLog()
    {
        return implode(chr(10), $this->output);
    }

    protected function getCacheDir(SyncConfiguration $syncConfiguration = null): string
    {
        $subFolder = 'default';
        if ($syncConfiguration !== null) {
            $subFolder = $syncConfiguration->getUid();
        }

        $cacheDir = Environment::getVarPath() . '/cache/newssync/' . $subFolder . '/';
        GeneralUtility::mkdir_deep($cacheDir);

        return $cacheDir;
    }

    /**
     * @param int $uid of storage folder
     */
    protected function clearCache($uid)
    {
        $pagets = BackendUtility::getPagesTSconfig($uid);
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

    final public function getPriority(): int
    {
        return static::PRIORITY;
    }

    /**
     * @param News $news
     * @param $uri
     * @throws ExistingTargetFileNameException
     */
    protected function addFalMediaByUri(SyncConfiguration $syncConfiguration, News $news, $uri): void
    {
        $newFile = $this->getFileByContent($syncConfiguration, $uri);

        foreach($news->getFalMedia() as $media) {
            if ($media instanceof FileReference) {
                if ($media->getFileUid() === $newFile->getUid()); {
                    $this->log('      FalMedia already assigned');
                    return;
                }
            }
        }

        $fileReference = new FileReference();
        $fileReference->setFileUid($newFile->getUid());
        $fileReference->setShowinpreview(true);
        $news->addFalMedia($fileReference);
    }

    protected function addDownloadFilesByUri(SyncConfiguration $syncConfiguration, News $news, string $uri, string $forceFileName = null): void
    {
        $newFile = $this->getFileByContent($syncConfiguration, $uri, $forceFileName);

        foreach ($news->getFalRelatedFiles()->toArray() as $file) {
            if ($file instanceof FileReference) {
                if ($file->getFileUid() === $newFile->getUid()) {
                    $this->log('      FalDownload already assigned');
                    return;
                }
            } else {
                throw new \Exception(get_class($file));
            }
        }

        $fileReference = new FileReference();
        $fileReference->setFileUid($newFile->getUid());
        $fileReference->setTitle($forceFileName ?? $newFile->getName());
        $news->addFalRelatedFile($fileReference);
    }

    protected function addUri(News $news, string $uri): void
    {
        foreach ($news->getRelatedLinks()->toArray() as $link) {
            if ($link instanceof Link) {
                if ($link->getUri() === $uri) {
                    $this->log('      Uri already assigned: ' . $uri);
                    return;
                }
            }
        }

        $this->log('      Uri assigned: ' . $uri);

        $link = new Link();
        $link->setCrdate(new \DateTime());
        $link->setPid($news->getPid());
        $link->setUri($uri);
        $news->addRelatedLink($link);
    }

    protected function getFileByContent(SyncConfiguration $syncConfiguration, string $uri, string $forcedFileName = null)
    {
        $tmpFileName = GeneralUtility::tempnam(static::IMPORT_ID);
        file_put_contents($tmpFileName, GeneralUtility::getUrl($uri));

        // first try to find a file
        $fileIndexRepository = GeneralUtility::makeInstance(FileIndexRepository::class);
        $files = $fileIndexRepository->findByContentHash(sha1(file_get_contents($tmpFileName)));
        if (count($files)) {
            foreach ($files as $fileInfo) {
                if ($fileInfo['storage'] > 0) {
                    if (file_exists($tmpFileName)) {
                        unlink($tmpFileName);
                    }
                    $file = GeneralUtility::makeInstance(ResourceFactory::class)->getFileObjectByStorageAndIdentifier(
                        $fileInfo['storage'],
                        $fileInfo['identifier']
                    );
                    $this->log('      found file:  ' . $file->getCombinedIdentifier());
                    return $file;
                }
            }
        }

        // not found, create one!
        $filename = $forcedFileName ?? basename(parse_url($uri, PHP_URL_PATH));
        $resourceFactory = GeneralUtility::makeInstance(ResourceFactory::class);

        $storage = $resourceFactory->getDefaultStorage();
        $folder = $storage->getDefaultFolder();
        if ($syncConfiguration->getProcessingfolder() !== '') {
            $storage = $this->storageRepository->findByCombinedIdentifier($syncConfiguration->getProcessingfolder());
            $folder = $resourceFactory->getObjectFromCombinedIdentifier($syncConfiguration->getProcessingfolder());
            if (!$folder instanceof Folder) {
                $this->log('      Problem using ' . $syncConfiguration->getProcessingfolder() . ' as storage folder');
                return null;
            }
        }

        if ($tmpFileName === null) {
            throw new \Exception('No tmp file found for: ' . $uri);
        }
        if (!is_file($tmpFileName) || !is_readable($tmpFileName)) {
            throw new \Exception('Tmp file not readable for: ' . $uri);
        }

        $targetFileName = 'newssync-' . hash('crc32b', $uri) . '-' . $filename;

        $file = $storage->createFile(
            $targetFileName,
            $folder
        );

        if ($file === null) {
            throw new \Exception('Could not create file for: ' . $uri . ' please check the processing folder');
        }

        $file->setContents(
            file_get_contents($tmpFileName)
        );

        if ($file->getExtension() === '') {
            list($type, $extension) = explode('/', $file->getMimeType(), 2);
            $file->rename($filename . '.' . $extension);
        }

        $this->log('      created file:  ' . $file->getCombinedIdentifier());

        if (file_exists($tmpFileName)) {
            unlink($tmpFileName);
        }

        return $file;
    }

    protected function generateSlug(int $uid, string $tableName = 'tx_news_domain_model_news', string $slugFieldName = 'path_segment'): string
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable($tableName);
        $queryBuilder->getRestrictions()->removeByType(HiddenRestriction::class);

        $record = $queryBuilder
            ->select('*')
            ->from($tableName)
            ->where($queryBuilder->expr()->eq('uid', $uid))
            ->executeQuery()
            ->fetchAssociative();

        $helper = GeneralUtility::makeInstance(
            SlugHelper::class,
            $tableName,
            $slugFieldName,
            $GLOBALS['TCA'][$tableName]['columns'][$slugFieldName]['config']
        );

        $value = $helper->generate($record, $record['pid']);
        $state = RecordStateFactory::forName($tableName)->fromArray($record, $record['pid'], $record['uid']);

        $value = $helper->buildSlugForUniqueInSite($value, $state);

        $queryBuilder
            ->update($tableName)
            ->where(
                $queryBuilder->expr()->eq('uid', $uid)
            )
            ->set($slugFieldName, $value)
            ->executeQuery();
        return $value;
    }

    protected function prepareNews(SyncConfiguration $syncConfiguration, string $syncKey, string $title): News
    {
        $news = $this->newsRepository->findOneByImportSourceAndImportId(static::IMPORT_ID, $syncKey);

        if ($news === null) {
            $news = new NewsDefault();
        }

        $news->setTitle($title);
        $news->setImportSource(static::IMPORT_ID);
        $news->setImportId($syncKey);
        $news->setPid($syncConfiguration->getStoragePid());
        $news->setType($syncConfiguration->getNewsType() ?? 0);
        $news->setCrdate(new \DateTime());
        $news->setDatetime(new \DateTime());
        $news->setType($syncConfiguration->getNewsType());

        $news->setHidden($syncConfiguration->getNewsIsHiddenAfterImport());
        $news->setIstopnews($syncConfiguration->getNewsIsTopNews());

        foreach ($syncConfiguration->getCategories() as $category) {
            if (!$news->getCategories()->contains($category)) {
                $news->addCategory($category);
            }
        }

        $this->log('Working on News: ' . $news->getTitle());

        return $news;
    }

    protected function saveNews(SyncConfiguration $syncConfiguration, News $news) {
        if ($this->persistenceManager->isNewObject($news)) {
            $this->log('  Importing ' . $news->getTitle());
            $this->newsRepository->add($news);
        } else {
            $this->log('  Updating ' . $news->getTitle());
            $this->newsRepository->update($news);
        }
        $this->persistenceManager->persistAll();
        $this->log('    with key: ' . $news->getImportId() . ' to ' . $syncConfiguration->getStoragePid());
        $slug = $this->generateSlug($this->persistenceManager->getIdentifierByObject($news));
        $this->log('    with slug: ' . $slug);
    }
}

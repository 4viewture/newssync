<?php

namespace Fourviewture\Newssync\Services\Provider;

use Fourviewture\Newssync\Domain\Model\SyncConfiguration;
use GeorgRinger\News\Domain\Model\FileReference;
use GeorgRinger\News\Domain\Model\News;
use GeorgRinger\News\Domain\Repository\NewsRepository;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Resource\Index\FileIndexRepository;
use TYPO3\CMS\Core\Resource\ResourceFactory;
use TYPO3\CMS\Core\Resource\ResourceStorage;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\File\BasicFileUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;

class RssImportService extends AbstractImportService
{
    const IMPORTID = 'newssync_rssimport';

    const CACHE_DIRECTORY = PATH_site . 'typo3temp/Cache/Data/newssync/SimplePie';

    /**
     * @var NewsRepository
     */
    protected $newsRepository;

    /**
     * @var ObjectManager
     */
    protected $objectManager;

    /**
     * @var PersistenceManager
     */
    protected $persistenceManager;

    /**
     * @var array
     */
    protected $emConfiguration = array();

    /**
     * RssImportService constructor.
     * @param NewsRepository $newsRepository
     * @param ObjectManager $objectManager
     * @param PersistenceManager $persistenceManager
     * @throws \TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationExtensionNotConfiguredException
     * @throws \TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationPathDoesNotExistException
     */
    public function __construct(NewsRepository $newsRepository, ObjectManager $objectManager, PersistenceManager $persistenceManager)
    {
        $this->newsRepository = $newsRepository;
        $this->objectManager = $objectManager;
        $this->persistenceManager = $persistenceManager;

        /** @var ExtensionConfiguration $configurationUtility */
        $configurationUtility = $this->objectManager->get(ExtensionConfiguration::class);
        $this->emConfiguration = $configurationUtility->get('newssync');

        if (!class_exists('SimplePie')) {
            require_once ExtensionManagementUtility::extPath('newssync') . 'Resources/Private/PHP/vendor/autoload.php';
        }
    }

    /**
     * @param SyncConfiguration $syncConfiguration
     * @return bool
     */
    public function canHandle(SyncConfiguration $syncConfiguration)
    {
        if (strpos($syncConfiguration->getDataFromUri(), '<rss ')) {
            return true;
        }
    }

    /**
     * @param SyncConfiguration $syncConfiguration
     */
    public function handle(SyncConfiguration $syncConfiguration)
    {
        parent::handle($syncConfiguration);
        GeneralUtility::mkdir_deep(self::CACHE_DIRECTORY);
        $simplePie = new \SimplePie();
        $simplePie->set_cache_location(self::CACHE_DIRECTORY);
        $simplePie->set_cache_duration($this->emConfiguration['simplePieCacheRssTime']['value']);
        $simplePie->set_feed_url($syncConfiguration->getUri());

        $this->log('  URL:   ' . $syncConfiguration->getUri());
        $this->log('  Cache: ' . self::CACHE_DIRECTORY);
        $this->log('  Cache: ' . $this->emConfiguration['simplePieCacheRssTime']['value']);

            $simplePie->init();
        $items = $simplePie->get_items();

        /** @var \SimplePie_Item $item */
        /** @var  News $news */
        foreach($items as $item) {
            $new = false;
            $syncKey = $syncConfiguration->getUid() . ':' . md5($item->get_link());
            $news = $this->newsRepository->findOneByImportSourceAndImportId(
                self::IMPORTID,
                $syncKey
            );
            if ($news === null) {
                $news = new News();
                $new = true;
            }

            $news->setImportSource(self::IMPORTID);
            $news->setImportId($syncKey);
            $news->setPid($syncConfiguration->getStoragePid());
            $news->setTitle($item->get_title());
            $news->setDescription($item->get_description());
            $news->setTeaser($item->get_description());
            $news->setBodytext($item->get_content());
            $news->setDatetime(new \DateTime($item->get_date()));

            if ($new) {
                $this->log('Importing "' . $news->getTitle() . '"');
                if ($item->get_enclosure(0) !== null) {
                    $this->log('    enclosure found');
                    $enclosure = $item->get_enclosure(0);
                    if (($enclosure->get_link() !== null) && ($enclosure->get_link() !== '//?#')) {
                        $this->log('      uri :' . $enclosure->get_link());
                        $enclosure = $item->get_enclosure(0);
                        $this->addFile($news, $enclosure->get_link());
                    } else {
                        $this->log('      skipped because of invalid uri ... ' . $enclosure->get_link());
                    }
                }

                foreach ($syncConfiguration->getCategories() as $category) {
                    $news->addCategory($category);
                }

                $news->setHidden($syncConfiguration->getNewsIsHiddenAfterImport());
                $news->setIstopnews($syncConfiguration->getNewsIsTopNews());
                $this->newsRepository->add($news);
            } else {
                $this->log('Updating "' . $news->getTitle() . '"');
                $this->newsRepository->update($news);
            }
            $this->log('    with key: ' . $syncKey . ' to ' . $syncConfiguration->getStoragePid());
        }

        $this->clearCache($syncConfiguration->getStoragePid());
    }

    /**
     * @param News $news
     * @param $uri
     */
    protected function addFile(News $news, $uri) {
        $filename = basename(parse_url($uri, PHP_URL_PATH));

        $resourceFactory = ResourceFactory::getInstance();

        $tmpFileName = GeneralUtility::tempnam('rss-import');
        file_put_contents($tmpFileName, GeneralUtility::getUrl($uri));

        $newFile = $this->getFileByContent($tmpFileName);
        if ($newFile === null) {
            $storage = $resourceFactory->getDefaultStorage();
            $newFile = $storage->addFile(
                $tmpFileName,
                $storage->getDefaultFolder(),
                'newssync-' . hash('crc32b', $uri) . '-' . $filename
            );
            $this->log('      created file:  ' . $newFile->getIdentifier());
        } else {
            $this->log('      existing file: ' . $newFile->getIdentifier());
        }

        $fileReference = new FileReference();
        $fileReference->setFileUid($newFile->getUid());
        $fileReference->setShowinpreview(true);
        $news->addFalMedia($fileReference);
        if (file_exists($tmpFileName)) {
            unlink($tmpFileName);
        }
    }

    /**
     * @param string $tmpName
     * @return null|\TYPO3\CMS\Core\Resource\File
     */
    protected function getFileByContent($tmpName)
    {
        $file = null;
        $files = FileIndexRepository::getInstance()->findByContentHash(sha1(file_get_contents($tmpName)));
        if (count($files)) {
            foreach ($files as $fileInfo) {
                if ($fileInfo['storage'] > 0) {
                    $file = ResourceFactory::getInstance()->getFileObjectByStorageAndIdentifier($fileInfo['storage'],
                        $fileInfo['identifier']);
                    break;
                }
            }
        }
        return $file;
    }
}

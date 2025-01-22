<?php

namespace Fourviewture\Newssync\Services\Provider;

use TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationExtensionNotConfiguredException;
use TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationPathDoesNotExistException;
use Fourviewture\Newssync\Services\Exception\OfflineException;
use TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException;
use TYPO3\CMS\Extbase\Persistence\Exception\UnknownObjectException;
use TYPO3\CMS\Core\Resource\Exception\ExistingTargetFileNameException;
use TYPO3\CMS\Core\Resource\File;
use Fourviewture\Newssync\Domain\Model\SyncConfiguration;
use GeorgRinger\News\Domain\Model\FileReference;
use GeorgRinger\News\Domain\Model\News;
use GeorgRinger\News\Domain\Repository\NewsRepository;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Core\Environment;
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
    public const IMPORT_ID = 'newssync_rssimport';

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
     * @param PersistenceManager $persistenceManager
     * @throws ExtensionConfigurationExtensionNotConfiguredException
     * @throws ExtensionConfigurationPathDoesNotExistException
     */
    public function __construct(NewsRepository $newsRepository, PersistenceManager $persistenceManager)
    {
        $this->newsRepository = $newsRepository;
        $this->persistenceManager = $persistenceManager;
        /** @var ExtensionConfiguration $configurationUtility */
        $configurationUtility = GeneralUtility::makeInstance(ExtensionConfiguration::class);
        $this->emConfiguration = $configurationUtility->get('newssync');
        if (!class_exists('SimplePie')) {
            require_once ExtensionManagementUtility::extPath('newssync') . 'Resources/Private/PHP/vendor/autoload.php';
        }
    }
    /**
     * @param SyncConfiguration $syncConfiguration
     * @return bool
     * @throws OfflineException
     */
    public function canHandle(SyncConfiguration $syncConfiguration)
    {
        if (strpos($syncConfiguration->getDataFromUri(), '<rss ')) {
            return true;
        }
    }

    /**
     * @param SyncConfiguration $syncConfiguration
     * @throws IllegalObjectTypeException
     * @throws UnknownObjectException
     * @throws ExistingTargetFileNameException
     */
    public function handle(SyncConfiguration $syncConfiguration)
    {
        parent::handle($syncConfiguration);

        $cacheDir = $this->getCacheDir($syncConfiguration);

        $simplePie = new \SimplePie();
        $simplePie->set_cache_location($cacheDir);
        $simplePie->set_cache_duration($this->emConfiguration['simplePieCacheRssTime']);
        $simplePie->set_feed_url($syncConfiguration->getUri());
        $this->log('  URL:   ' . $syncConfiguration->getUri());
        $this->log('  Cache: ' . $cacheDir);
        $this->log('  Cache: ' . $this->emConfiguration['simplePieCacheRssTime']);
        $simplePie->init();
        $items = $simplePie->get_items();
        /** @var \SimplePie_Item $item */
        /** @var  News $news */
        foreach ($items as $item) {
            $syncKey = $syncConfiguration->getUid() . ':' . md5($item->get_link());
            $news = $this->prepareNews($syncConfiguration, $syncKey, $item->get_title());

            $news->setDescription($item->get_description());
            $news->setTeaser($item->get_description());
            $news->setBodytext($item->get_content());
            $news->setDatetime(new \DateTime($item->get_date()));
            if ($this->persistenceManager->isNewObject($news)) {
                if ($item->get_enclosure(0) !== null) {
                    $this->log('    enclosure found');
                    $enclosure = $item->get_enclosure(0);
                    if ($enclosure->get_link() !== null && $enclosure->get_link() !== '//?#') {
                        $this->log('      uri :' . $enclosure->get_link());
                        $enclosure = $item->get_enclosure(0);
                        $this->addFalMediaByUri($syncConfiguration, $news, $enclosure->get_link());
                    } else {
                        $this->log('      skipped because of invalid uri ... ' . $enclosure->get_link());
                    }
                }
                $this->newsRepository->add($news);
                $this->persistenceManager->persistAll();
            }

            $this->saveNews($syncConfiguration, $news);
        }
        $this->clearCache($syncConfiguration->getStoragePid());
    }
}

<?php

namespace Fourviewture\Newssync\Domain\Model;

use TYPO3\CMS\Extbase\Annotation as Extbase;
use Fourviewture\Newssync\Services\Exception\OfflineException;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Domain\Model\Category;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

/***************************************************************
 *
 *  Copyright notice
 *
 *  (c) 2016 Kay Strobach <typo3@kay-strobach.de>
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/
/**
 * SynConfiguration
 */
class SyncConfiguration extends AbstractEntity
{
    /**
     * @var string
     * @Extbase\ORM\Transient
     */
    protected $transmissionBuffer = null;
    /**
     * title
     *
     * @var string
     */
    protected $title = '';
    /**
     * uri
     *
     * @var string
     */
    protected $uri = '';
    /**
     * description
     *
     * @var string
     */
    protected $description = '';
    /**
     * processingfolder
     *
     * @var string
     */
    protected $processingfolder = '';
    /**
     * lastsync
     *
     * @var \DateTime
     */
    protected $lastsync = null;
    /**
     * lastsynclog
     *
     * @var string
     */
    protected $lastsynclog = '';
    /**
     * autoClearCacheForPlugin
     *
     * @var bool
     */
    protected $autoClearCacheForPlugin = false;
    /**
     * storagePid
     *
     * @var int
     */
    protected $storagePid = null;
    /**
     * clearCachePages
     *
     * @var ObjectStorage<Page>
     */
    protected $clearCachePages = null;
    /**
     * newsIsHiddenAfterImport
     *
     * @var bool
     */
    protected $newsIsHiddenAfterImport = false;
    /**
     * newsIsTopNews
     *
     * @var bool
     */
    protected $newsIsTopNews = false;
    /**
     * Categories
     *
     * @var ObjectStorage<\GeorgRinger\News\Domain\Model\Category>
     */
    protected $categories;

    /**
     * @var int
     */
    protected $newsType = 0;

    /**
     * Returns the title
     *
     * @return string $title
     */
    public function getTitle()
    {
        return $this->title;
    }
    /**
     * Sets the title
     *
     * @param string $title
     * @return void
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }
    /**
     * Returns the uri
     *
     * @return string $uri
     */
    public function getUri()
    {
        return $this->uri;
    }
    /**
     * Sets the uri
     *
     * @param string $uri
     * @return void
     */
    public function setUri($uri)
    {
        $this->uri = $uri;
    }
    /**
     * Returns the description
     *
     * @return string $description
     */
    public function getDescription()
    {
        return $this->description;
    }
    /**
     * Sets the description
     *
     * @param string $description
     * @return void
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }
    /**
     * Returns the lastsync
     *
     * @return \DateTime $lastsync
     */
    public function getLastsync()
    {
        return $this->lastsync;
    }
    /**
     * Sets the lastsync
     *
     * @param \DateTime $lastsync
     * @return void
     */
    public function setLastsync(\DateTime $lastsync)
    {
        $this->lastsync = $lastsync;
    }
    /**
     * Returns the processingfolder
     *
     * @return string $processingfolder
     */
    public function getProcessingfolder()
    {
        return $this->processingfolder;
    }
    /**
     * Sets the processingfolder
     *
     * @param string $processingfolder
     * @return void
     */
    public function setProcessingfolder($processingfolder)
    {
        $this->processingfolder = $processingfolder;
    }
    /**
     * Returns the lastsynclog
     *
     * @return string $lastsynclog
     */
    public function getLastsynclog()
    {
        return $this->lastsynclog;
    }
    /**
     * Sets the lastsynclog
     *
     * @param string $lastsynclog
     * @return void
     */
    public function setLastsynclog($lastsynclog)
    {
        $this->lastsynclog = $lastsynclog;
    }
    /**
     * __construct
     */
    public function __construct()
    {
        //Do not remove the next line: It would break the functionality
        $this->initStorageObjects();
    }
    /**
     * Initializes all ObjectStorage properties
     * Do not modify this method!
     * It will be rewritten on each save in the extension builder
     * You may modify the constructor of this class instead
     *
     * @return void
     */
    protected function initStorageObjects()
    {
        $this->clearCachePages = new ObjectStorage();
    }
    /**
     * Adds a Page
     *
     * @param Page $clearCachePage
     * @return void
     */
    public function addClearCachePage(Page $clearCachePage)
    {
        $this->clearCachePages->attach($clearCachePage);
    }
    /**
     * Removes a Page
     *
     * @param Page $clearCachePageToRemove The Page to be removed
     * @return void
     */
    public function removeClearCachePage(Page $clearCachePageToRemove)
    {
        $this->clearCachePages->detach($clearCachePageToRemove);
    }
    /**
     * Returns the clearCachePages
     *
     * @return ObjectStorage<Page> $clearCachePages
     */
    public function getClearCachePages()
    {
        return $this->clearCachePages;
    }
    /**
     * Sets the clearCachePages
     *
     * @param ObjectStorage<Page> $clearCachePages
     * @return void
     */
    public function setClearCachePages(ObjectStorage $clearCachePages)
    {
        $this->clearCachePages = $clearCachePages;
    }
    /**
     * Returns the storagePid
     *
     * @return int $storagePid
     */
    public function getStoragePid()
    {
        return $this->storagePid;
    }
    /**
     * Sets the storagePid
     *
     * @param int $storagePid
     * @return void
     */
    public function setStoragePid($storagePid)
    {
        $this->storagePid = $storagePid;
    }
    /**
     * Returns the autoClearCacheForPlugin
     *
     * @return bool $autoClearCacheForPlugin
     */
    public function getAutoClearCacheForPlugin()
    {
        return $this->autoClearCacheForPlugin;
    }
    /**
     * Sets the autoClearCacheForPlugin
     *
     * @param bool $autoClearCacheForPlugin
     * @return void
     */
    public function setAutoClearCacheForPlugin($autoClearCacheForPlugin)
    {
        $this->autoClearCacheForPlugin = $autoClearCacheForPlugin;
    }
    /**
     * Returns the boolean state of autoClearCacheForPlugin
     *
     * @return bool
     */
    public function isAutoClearCacheForPlugin()
    {
        return $this->autoClearCacheForPlugin;
    }
    /**
     * @throws OfflineException
     * @return mixed
     */
    public function getDataFromUri()
    {
        if ($this->transmissionBuffer !== null) {
            return $this->transmissionBuffer;
        }
        $report = null;
        $content = GeneralUtility::getUrl($this->getUri(), 0, false, $report);
        if ($content === false) {
            throw new OfflineException($this->uri . ' seems to be offline');
        }
        $this->transmissionBuffer = $content;
        return $content;
    }
    /**
     * Returns the newsIsHiddenAfterImport
     *
     * @return bool $newsIsHiddenAfterImport
     */
    public function getNewsIsHiddenAfterImport()
    {
        return $this->newsIsHiddenAfterImport;
    }
    /**
     * Sets the newsIsHiddenAfterImport
     *
     * @param bool $newsIsHiddenAfterImport
     * @return void
     */
    public function setNewsIsHiddenAfterImport($newsIsHiddenAfterImport)
    {
        $this->newsIsHiddenAfterImport = $newsIsHiddenAfterImport;
    }
    /**
     * Returns the boolean state of newsIsHiddenAfterImport
     *
     * @return bool
     */
    public function isNewsIsHiddenAfterImport()
    {
        return $this->newsIsHiddenAfterImport;
    }
    /**
     * Returns the newsIsTopNews
     *
     * @return bool $newsIsTopNews
     */
    public function getNewsIsTopNews()
    {
        return $this->newsIsTopNews;
    }
    /**
     * Sets the newsIsTopNews
     *
     * @param bool $newsIsTopNews
     * @return void
     */
    public function setNewsIsTopNews($newsIsTopNews)
    {
        $this->newsIsTopNews = $newsIsTopNews;
    }
    /**
     * Returns the boolean state of newsIsTopNews
     *
     * @return bool
     */
    public function isNewsIsTopNews()
    {
        return $this->newsIsTopNews;
    }
    /**
     * Adds a Category
     *
     * @param \GeorgRinger\News\Domain\Model\Category $category
     * @return void
     */
    public function addCategory(Category $category)
    {
        $this->categories->attach($category);
    }
    /**
     * Removes a Category
     *
     * @param \GeorgRinger\News\Domain\Model\Category $categoryToRemove The Category to be removed
     * @return void
     */
    public function removeCategory(Category $categoryToRemove)
    {
        $this->categories->detach($categoryToRemove);
    }
    /**
     * Returns the categories
     *
     * @return ObjectStorage<\GeorgRinger\News\Domain\Model> $categories
     */
    public function getCategories()
    {
        return $this->categories;
    }
    /**
     * Sets the categories
     *
     * @param ObjectStorage<\GeorgRinger\News\Domain\Model> $categories
     * @return void
     */
    public function setCategories(ObjectStorage $categories)
    {
        $this->categories = $categories;
    }

    /**
     * @return int
     */
    public function getNewsType(): int
    {
        return $this->newsType ?? 0;
    }

    /**
     * @param int $newsType
     */
    public function setNewsType(int $newsType): void
    {
        $this->newsType = $newsType;
    }
}

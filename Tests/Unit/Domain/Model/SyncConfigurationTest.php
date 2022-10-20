<?php

namespace Fourviewture\Newssync\Tests\Unit\Domain\Model;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2016 Kay Strobach <typo3@kay-strobach.de>
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
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
 * Test case for class \Fourviewture\Newssync\Domain\Model\SyncConfiguration.
 *
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 * @author Kay Strobach <typo3@kay-strobach.de>
 */
class SyncConfigurationTest extends \TYPO3\CMS\Core\Tests\UnitTestCase
{
    /**
     * @var \Fourviewture\Newssync\Domain\Model\SyncConfiguration
     */
    protected $subject = null;

    public function setUp()
    {
        $this->subject = new \Fourviewture\Newssync\Domain\Model\SyncConfiguration();
    }

    public function tearDown()
    {
        unset($this->subject);
    }

    /**
     * @test
     */
    public function getTitleReturnsInitialValueForString()
    {
        $this->assertSame(
            '',
            $this->subject->getTitle()
        );
    }

    /**
     * @test
     */
    public function setTitleForStringSetsTitle()
    {
        $this->subject->setTitle('Conceived at T3CON10');

        $this->assertAttributeEquals(
            'Conceived at T3CON10',
            'title',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getUriReturnsInitialValueForString()
    {
        $this->assertSame(
            '',
            $this->subject->getUri()
        );
    }

    /**
     * @test
     */
    public function setUriForStringSetsUri()
    {
        $this->subject->setUri('Conceived at T3CON10');

        $this->assertAttributeEquals(
            'Conceived at T3CON10',
            'uri',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getDescriptionReturnsInitialValueForString()
    {
        $this->assertSame(
            '',
            $this->subject->getDescription()
        );
    }

    /**
     * @test
     */
    public function setDescriptionForStringSetsDescription()
    {
        $this->subject->setDescription('Conceived at T3CON10');

        $this->assertAttributeEquals(
            'Conceived at T3CON10',
            'description',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getProcessingfolderReturnsInitialValueForString()
    {
        $this->assertSame(
            '',
            $this->subject->getProcessingfolder()
        );
    }

    /**
     * @test
     */
    public function setProcessingfolderForStringSetsProcessingfolder()
    {
        $this->subject->setProcessingfolder('Conceived at T3CON10');

        $this->assertAttributeEquals(
            'Conceived at T3CON10',
            'processingfolder',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getLastsyncReturnsInitialValueForDateTime()
    {
        $this->assertEquals(
            null,
            $this->subject->getLastsync()
        );
    }

    /**
     * @test
     */
    public function setLastsyncForDateTimeSetsLastsync()
    {
        $dateTimeFixture = new \DateTime();
        $this->subject->setLastsync($dateTimeFixture);

        $this->assertAttributeEquals(
            $dateTimeFixture,
            'lastsync',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getLastsynclogReturnsInitialValueForString()
    {
        $this->assertSame(
            '',
            $this->subject->getLastsynclog()
        );
    }

    /**
     * @test
     */
    public function setLastsynclogForStringSetsLastsynclog()
    {
        $this->subject->setLastsynclog('Conceived at T3CON10');

        $this->assertAttributeEquals(
            'Conceived at T3CON10',
            'lastsynclog',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getAutoClearCacheForPluginReturnsInitialValueForBool()
    {
        $this->assertSame(
            false,
            $this->subject->getAutoClearCacheForPlugin()
        );
    }

    /**
     * @test
     */
    public function setAutoClearCacheForPluginForBoolSetsAutoClearCacheForPlugin()
    {
        $this->subject->setAutoClearCacheForPlugin(true);

        $this->assertAttributeEquals(
            true,
            'autoClearCacheForPlugin',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getNewsIsHiddenAfterImportReturnsInitialValueForBool()
    {
        $this->assertSame(
            false,
            $this->subject->getNewsIsHiddenAfterImport()
        );
    }

    /**
     * @test
     */
    public function setNewsIsHiddenAfterImportForBoolSetsNewsIsHiddenAfterImport()
    {
        $this->subject->setNewsIsHiddenAfterImport(true);

        $this->assertAttributeEquals(
            true,
            'newsIsHiddenAfterImport',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getNewsIsTopNewsReturnsInitialValueForBool()
    {
        $this->assertSame(
            false,
            $this->subject->getNewsIsTopNews()
        );
    }

    /**
     * @test
     */
    public function setNewsIsTopNewsForBoolSetsNewsIsTopNews()
    {
        $this->subject->setNewsIsTopNews(true);

        $this->assertAttributeEquals(
            true,
            'newsIsTopNews',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getStoragePidReturnsInitialValueForPage()
    {
        $this->assertEquals(
            null,
            $this->subject->getStoragePid()
        );
    }

    /**
     * @test
     */
    public function setStoragePidForPageSetsStoragePid()
    {
        $storagePidFixture = new \Fourviewture\Newssync\Domain\Model\Page();
        $this->subject->setStoragePid($storagePidFixture);

        $this->assertAttributeEquals(
            $storagePidFixture,
            'storagePid',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getClearCachePagesReturnsInitialValueForPage()
    {
        $newObjectStorage = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $this->assertEquals(
            $newObjectStorage,
            $this->subject->getClearCachePages()
        );
    }

    /**
     * @test
     */
    public function setClearCachePagesForObjectStorageContainingPageSetsClearCachePages()
    {
        $clearCachePage = new \Fourviewture\Newssync\Domain\Model\Page();
        $objectStorageHoldingExactlyOneClearCachePages = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $objectStorageHoldingExactlyOneClearCachePages->attach($clearCachePage);
        $this->subject->setClearCachePages($objectStorageHoldingExactlyOneClearCachePages);

        $this->assertAttributeEquals(
            $objectStorageHoldingExactlyOneClearCachePages,
            'clearCachePages',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function addClearCachePageToObjectStorageHoldingClearCachePages()
    {
        $clearCachePage = new \Fourviewture\Newssync\Domain\Model\Page();
        $clearCachePagesObjectStorageMock = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage', array('attach'), array(), '', false);
        $clearCachePagesObjectStorageMock->expects($this->once())->method('attach')->with($this->equalTo($clearCachePage));
        $this->inject($this->subject, 'clearCachePages', $clearCachePagesObjectStorageMock);

        $this->subject->addClearCachePage($clearCachePage);
    }

    /**
     * @test
     */
    public function removeClearCachePageFromObjectStorageHoldingClearCachePages()
    {
        $clearCachePage = new \Fourviewture\Newssync\Domain\Model\Page();
        $clearCachePagesObjectStorageMock = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage', array('detach'), array(), '', false);
        $clearCachePagesObjectStorageMock->expects($this->once())->method('detach')->with($this->equalTo($clearCachePage));
        $this->inject($this->subject, 'clearCachePages', $clearCachePagesObjectStorageMock);

        $this->subject->removeClearCachePage($clearCachePage);
    }
}

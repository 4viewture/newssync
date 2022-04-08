<?php
namespace Fourviewture\Newssync\Tests\Unit\Controller;

use TYPO3\TestingFramework\Core\Unit\UnitTestCase;
use Fourviewture\Newssync\Controller\SyncConfigurationController;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
use TYPO3\CMS\Extbase\Mvc\View\ViewInterface;
use Fourviewture\Newssync\Domain\Model\SyncConfiguration;
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
 * Test case for class Fourviewture\Newssync\Controller\SyncConfigurationController.
 *
 * @author Kay Strobach <typo3@kay-strobach.de>
 */
class SyncConfigurationControllerTest extends UnitTestCase
{

    /**
     * @var SyncConfigurationController
     */
    protected $subject = null;

    public function setUp()
    {
        $this->subject = $this->getMock('Fourviewture\\Newssync\\Controller\\SyncConfigurationController', array('redirect', 'forward', 'addFlashMessage'), array(), '', false);
    }

    public function tearDown()
    {
        unset($this->subject);
    }

    /**
     * @test
     */
    public function listActionFetchesAllSyncConfigurationsFromRepositoryAndAssignsThemToView()
    {
        $allSyncConfigurations = $this->getMock(ObjectStorage::class, array(), array(), '', false);

        $syncConfigurationRepository = $this->getMock('Fourviewture\\Newssync\\Domain\\Repository\\SyncConfigurationRepository', array('findAll'), array(), '', false);
        $syncConfigurationRepository->expects($this->once())->method('findAll')->will($this->returnValue($allSyncConfigurations));
        $this->inject($this->subject, 'syncConfigurationRepository', $syncConfigurationRepository);

        $view = $this->getMock(ViewInterface::class);
        $view->expects($this->once())->method('assign')->with('syncConfigurations', $allSyncConfigurations);
        $this->inject($this->subject, 'view', $view);

        $this->subject->listAction();
    }

    /**
     * @test
     */
    public function showActionAssignsTheGivenSyncConfigurationToView()
    {
        $syncConfiguration = new SyncConfiguration();

        $view = $this->getMock(ViewInterface::class);
        $this->inject($this->subject, 'view', $view);
        $view->expects($this->once())->method('assign')->with('syncConfiguration', $syncConfiguration);

        $this->subject->showAction($syncConfiguration);
    }
}

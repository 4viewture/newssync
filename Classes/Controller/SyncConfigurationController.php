<?php
namespace Fourviewture\Newssync\Controller;

use Fourviewture\Newssync\Domain\Model\SyncConfiguration;
use Fourviewture\Newssync\Domain\Repository\SyncConfigurationRepository;
use Fourviewture\Newssync\Services\ImportService;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Mvc\Exception\StopActionException;
use TYPO3\CMS\Extbase\Mvc\Exception\UnsupportedRequestTypeException;
use TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException;
use TYPO3\CMS\Extbase\Persistence\Exception\UnknownObjectException;

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
 * SyncConfigurationController
 */
class SyncConfigurationController extends ActionController
{

    /**
     * syncConfigurationRepository
     *
     * @var \Fourviewture\Newssync\Domain\Repository\SyncConfigurationRepository
     * @inject
     */
    protected $syncConfigurationRepository = NULL;

    /**
     * @var \Fourviewture\Newssync\Services\ImportService
     * @inject
     */
    protected $importService;

    /**
     * action list
     *
     * @return void
     */
    public function listAction()
    {
        $synConfigurations = $this->syncConfigurationRepository->findAll();
        $this->view->assign('syncConfigurations', $synConfigurations);
    }

    /**
     * action show
     *
     * @param SyncConfiguration $syncConfiguration
     * @return void
     */
    public function showAction(SyncConfiguration $syncConfiguration)
    {
        $this->view->assign('syncConfiguration', $syncConfiguration);
    }

    /**
     * action refreshData
     *
     * @param SyncConfiguration $syncConfiguration
     * @return void
     * @throws StopActionException
     * @throws UnsupportedRequestTypeException
     * @throws IllegalObjectTypeException
     * @throws UnknownObjectException
     */
    public function refreshDataAction(SyncConfiguration $syncConfiguration)
    {
        try {
            $this->importService->import($syncConfiguration);
        } catch (\Exception $e) {
            $syncConfiguration->setLastsync(new \DateTime('now'));
            $syncConfiguration->setLastsynclog($e->getMessage() . PHP_EOL . $e->getTraceAsString());
        }

        $this->syncConfigurationRepository->update($syncConfiguration);
        $this->addFlashMessage('Refreshed: ' . $syncConfiguration->getTitle());
        $this->redirect('list');
    }
}

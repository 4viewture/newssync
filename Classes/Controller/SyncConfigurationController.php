<?php
namespace Fourviewture\Newssync\Controller;

use Psr\Http\Message\ResponseInterface;
use \TYPO3\CMS\Backend\Attribute\AsController;
use Fourviewture\Newssync\Domain\Model\SyncConfiguration;
use Fourviewture\Newssync\Domain\Repository\SyncConfigurationRepository;
use Fourviewture\Newssync\Services\ImportService;
use TYPO3\CMS\Backend\Template\ModuleTemplate;
use TYPO3\CMS\Backend\Template\ModuleTemplateFactory;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Mvc\Exception\StopActionException;
use TYPO3\CMS\Extbase\Mvc\Exception\UnsupportedRequestTypeException;
use TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException;
use TYPO3\CMS\Extbase\Persistence\Exception\UnknownObjectException;
use TYPO3\CMS\Extbase\Annotation as Extbase;

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

#[AsController]
class SyncConfigurationController extends ActionController
{
    protected ?SyncConfigurationRepository $syncConfigurationRepository = null;

    protected ?ImportService $importService;

    protected ?ModuleTemplateFactory $moduleTemplateFactory;

    public function injectModuleTemplateFactory(ModuleTemplateFactory $moduleTemplateFactory)
    {
        $this->moduleTemplateFactory = $moduleTemplateFactory;
    }

    public function injectSyncConfigurationRepository(SyncConfigurationRepository $repository)
    {
        $this->syncConfigurationRepository = $repository;
    }

    public function injectImportService(ImportService $service)
    {
        $this->importService = $service;
    }


    public function listAction(): ResponseInterface
    {
        $syncConfigurations = $this->syncConfigurationRepository->findAllIncludingDisabled();
        $moduleTemplate = $this->moduleTemplateFactory->create($this->request);
        // Adding title, menus, buttons, etc. using $moduleTemplate ...
        $moduleTemplate->assign('syncConfigurations', $syncConfigurations);
        $moduleTemplate->assign('content', $this->view->render());
        return $this->htmlResponse($moduleTemplate->render('SyncConfiguration/List'));
    }
    /**
     * action show
     *
     * @param int $sync_preview
     * @return void
     */
    public function showAction(int $sync_preview): ResponseInterface
    {
        $syncConfiguration = $this->syncConfigurationRepository->findOneIncludingDeletedByUid($sync_preview);
        $moduleTemplate = $this->moduleTemplateFactory->create($this->request);
        // Adding title, menus, buttons, etc. using $moduleTemplate ...
        $moduleTemplate->assign('syncConfiguration', $syncConfiguration);
        $moduleTemplate->assign('content', $this->view->render());
        return $this->htmlResponse($moduleTemplate->render('SyncConfiguration/Show'));
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
    public function refreshDataAction(int $sync_preview): ResponseInterface
    {
        $syncConfiguration = $this->syncConfigurationRepository->findOneIncludingDeletedByUid($sync_preview);

        try {
            $this->importService->import($syncConfiguration);
        } catch (\Exception $e) {
            $syncConfiguration->setLastsync(new \DateTime('now'));
            $syncConfiguration->setLastsynclog($e->getMessage() . PHP_EOL . $e->getTraceAsString());
        }
        $this->syncConfigurationRepository->update($syncConfiguration);
        $this->addFlashMessage('Refreshed: ' . $syncConfiguration->getTitle());
        return $this->redirect('list');
    }
}

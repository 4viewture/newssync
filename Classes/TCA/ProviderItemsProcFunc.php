<?php

declare(strict_types=1);

namespace Fourviewture\Newssync\TCA;

use Fourviewture\Newssync\Domain\Model\SyncConfiguration;
use Fourviewture\Newssync\Services\Provider\AbstractImportService;
use TYPO3\CMS\Backend\Form\FormDataProvider\TcaSelectItems;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class ProviderItemsProcFunc
{
    /**
     * @param array $params
     * @param array $config
     * @param array $TSconfig
     * @param string $table
     * @param array $row
     * @return void
     */
    public static function itemsProcFunc(array &$params, TcaSelectItems $config): void
    {
        $entries = $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['newssync']['importservices'];

        $config = new SyncConfiguration();
        $config->setUri($params['row']['uri']);


        foreach ($entries as $entry) {
            $label = $entry;
            // check if the class can provide a translation label with interface


            /** @var AbstractImportService $obj */
            #$obj = GeneralUtility::makeInstance($entry);
            #$canHandle = $obj->canHandle($config) ?? false;
            $canHandle = false;

            $params['items'][] = [
                'label' => $entry,
                'value' => $entry,
                'icon' => $canHandle ? 'status-dialog-ok' : 'status-dialog-error',
            ];
        }
    }
}

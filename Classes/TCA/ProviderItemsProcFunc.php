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
            $canHandle = false;
            // check if the class can provide a translation label with interface

            try {
                /** @var AbstractImportService $obj */
                $obj = GeneralUtility::makeInstance($entry);
                $label = $obj->getLabelForTca();

                $canHandle = $obj->canHandle($config) ?? false;
            } catch (\Exception $e) {
                $label .= ' - ❌️ ' . $e->getMessage();
                $canHandle = false;
            }

            if (!$canHandle) {
                $label = '⚠ ' . $label;
            }

            $params['items'][] = [
                'label' => $label,
                'value' => $entry,
                'icon' => $canHandle ? 'status-dialog-ok' : 'status-dialog-error',
            ];
        }
    }
}

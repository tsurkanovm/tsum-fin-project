<?php

declare(strict_types=1);

namespace Tsum\CashFlowImport\Block\Adminhtml\Import\Button;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class Import implements ButtonProviderInterface
{
    /**
     * @return array
     */
    public function getButtonData(): array
    {
        return [
            'label'      => __('Import'),
            'class'      => 'action-secondary',
            'sort_order' => 20,
            'on_click'   => '',
            'data_attribute' => [
                'mage-init' => [
                    'buttonAdapter' => [
                        'actions' => [
                            [
                                'targetName' => 'cf_import_staging_transaction_form.cf_import_staging_transaction_form',
                                'actionName' => 'save',
                                'params' => [
                                    false
                                ]
                            ]
                        ]
                    ]
                ]
            ],
        ];
    }
}

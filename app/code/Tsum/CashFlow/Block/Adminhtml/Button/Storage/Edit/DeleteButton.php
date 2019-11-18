<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Tsum\CashFlow\Block\Adminhtml\Button\Storage\Edit;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;
use Tsum\CashFlow\Block\Adminhtml\Button\GenericButton;
use Tsum\CashFlow\Model\Storage;

/**
 * Class DeleteButton
 */
class DeleteButton implements ButtonProviderInterface
{
    /**
     * @var GenericButton
     */
    protected $urlButtonHelper;

    /**
     * DeleteButton constructor.
     * @param GenericButton $urlButtonHelper
     */
    public function __construct(GenericButton $urlButtonHelper)
    {
        $this->urlButtonHelper = $urlButtonHelper;
    }


    /**
     * @inheritDoc
     */
    public function getButtonData()
    {
        $idFieldName = Storage::ENTITY_ID;
        $data = [];
        if ($id = $this->urlButtonHelper->getVerifiedEntityId($idFieldName)) {
            $data = [
                'label' => __('Delete Storage'),
                'class' => 'delete',
                'on_click' => 'deleteConfirm(\'' . __(
                    'Are you sure you want to do this?'
                ) . '\', \'' . $this->urlButtonHelper->getDeleteUrl($idFieldName, $id) . '\', {"data": {}})',
                'sort_order' => 20,
            ];
        }

        return $data;
    }
}

<?php
namespace Tsum\CashFlow\Block\Adminhtml\Button\Transfer;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;
use Tsum\CashFlow\Block\Adminhtml\Button\GenericButton;
use Tsum\CashFlow\Model\Transfer;

class DeleteButton implements ButtonProviderInterface
{
    /**
     * @var GenericButton
     */
    private $urlButtonHelper;

    /**
     * @param GenericButton $urlButtonHelper
     */
    public function __construct(GenericButton $urlButtonHelper)
    {
        $this->urlButtonHelper = $urlButtonHelper;
    }

    /**
     * @inheritDoc
     */
    public function getButtonData() : array
    {
        $idFieldName = Transfer::ENTITY_ID;
        $data = [];
        if ($id = $this->urlButtonHelper->getVerifiedEntityId($idFieldName)) {
            $data = [
                'label' => __('Delete Transfer'),
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

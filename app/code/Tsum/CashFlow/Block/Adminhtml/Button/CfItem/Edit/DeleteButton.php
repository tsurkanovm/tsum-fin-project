<?php
namespace Tsum\CashFlow\Block\Adminhtml\Button\CfItem\Edit;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;
use Tsum\CashFlow\Block\Adminhtml\Button\GenericButton;
use Tsum\CashFlow\Model\CfItem;

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
        $idFieldName = CfItem::ENTITY_ID;
        $data = [];
        if ($id = $this->urlButtonHelper->getVerifiedEntityId($idFieldName)) {
            $data = [
                'label' => __('Delete Cash Flow Item'),
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

<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Tsum\CashFlow\Controller\Adminhtml\CfItem;

use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Tsum\CashFlow\Model\ResourceModel\CfItem;
use Tsum\CashFlow\Model\CfItem as CfItemModel;
use Tsum\CashFlow\Model\CfItemFactory;

/**
 * CashFlow storage grid inline edit controller
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class InlineEdit extends \Magento\Backend\App\Action
{
    /**
     * Authorization level of a basic admin session
     */
    const ADMIN_RESOURCE = 'Tsum_CashFlow::cfitem';

    /**
     * @var CfItem
     */
    protected $cfItem;

    /**
     * @var CfItemFactory
     */
    protected $cfItemFactory;

    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    protected $jsonFactory;

    /**
     * InlineEdit constructor.
     * @param Context $context
     * @param CfItem $cfItem
     * @param CfItemFactory $cfItemFactory
     * @param JsonFactory $jsonFactory
     */
    public function __construct(
        Context $context,
        CfItem $cfItem,
        CfItemFactory $cfItemFactory,
        JsonFactory $jsonFactory
    ) {
        parent::__construct($context);
        $this->cfItem = $cfItem;
        $this->cfItemFactory = $cfItemFactory;
        $this->jsonFactory = $jsonFactory;
    }

    /**
     * @return \Magento\Framework\Controller\ResultInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function execute()
    {
        /** @var \Magento\Framework\Controller\Result\Json $resultJson */
        $resultJson = $this->jsonFactory->create();
        $error = false;
        $messages = [];

        $cfItems = $this->getRequest()->getParam('items', []);
        if (!($this->getRequest()->getParam('isAjax') && count($cfItems))) {
            return $resultJson->setData([
                'messages' => [__('Please correct the data sent.')],
                'error' => true,
            ]);
        }

        foreach (array_keys($cfItems) as $itemId) {
            $model = $this->cfItemFactory->create();
            $this->cfItem->load($model, $itemId);
            try {
                $model->setData($cfItems[$itemId]);
                $this->cfItem->save($model);
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $messages[] = $this->getErrorWithPageId($model, $e->getMessage());
                $error = true;
            } catch (\RuntimeException $e) {
                $messages[] = $this->getErrorWithPageId($model, $e->getMessage());
                $error = true;
            } catch (\Exception $e) {
                $messages[] = $this->getErrorWithPageId(
                    $model,
                    __('Something went wrong while saving the cash flow item.')
                );
                $error = true;
            }
        }

        return $resultJson->setData([
            'messages' => $messages,
            'error' => $error
        ]);
    }

    /**
     * Add storage title to error message
     *
     * @param CfItemModel $model
     * @param string $errorText
     * @return string
     */
    protected function getErrorWithPageId(CfItemModel $model, $errorText)
    {
        return '[Cash Flow ID: ' . $model->getId() . '] ' . $errorText;
    }
}

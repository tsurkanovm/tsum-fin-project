<?php

namespace Tsum\CashFlow\Controller\Adminhtml\Transfer;

use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Controller\ResultInterface;
use Tsum\CashFlow\Model\Transfer;
use Tsum\CashFlow\Model\ResourceModel\Transfer as TransferResource;
use Tsum\CashFlow\Model\TransferFactory;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class InlineEdit extends \Magento\Backend\App\Action
{
    const ADMIN_RESOURCE = 'Tsum_CashFlow::incomes';

    /**
     * @var TransferFactory
     */
    private $transferFactory;

    /**
     * @var TransferResource
     */
    private $transferResource;

    /**
     * @var JsonFactory
     */
    private $jsonFactory;

    /**
     * @param Context $context
     * @param TransferFactory $transferFactory
     * @param TransferResource $transferResource
     * @param JsonFactory $jsonFactory
     */
    public function __construct(
        Context $context,
        TransferFactory $transferFactory,
        TransferResource $transferResource,
        JsonFactory $jsonFactory
    ) {
        parent::__construct($context);
        $this->transferFactory = $transferFactory;
        $this->transferResource = $transferResource;
        $this->jsonFactory = $jsonFactory;
    }

    /**
     * @return ResultInterface
     */
    public function execute()
    {
        $resultJson = $this->jsonFactory->create();
        $error = false;
        $messages = [];

        $transfer = $this->getRequest()->getParam('items', []);
        if (!($this->getRequest()->getParam('isAjax') && count($transfer))) {
            return $resultJson->setData([
                'messages' => [__('Please correct the data sent.')],
                'error' => true,
            ]);
        }

        foreach (array_keys($transfer) as $itemId) {
            /** @var Transfer $model */
            $model = $this->transferFactory->create();
            $this->transferResource->load($model, $itemId);
            try {
                $model->setData($transfer[$itemId]);
                $this->transferResource->save($model);
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
     * @param Transfer $model
     * @param string $errorText
     * @return string
     */
    protected function getErrorWithPageId(Transfer $model, $errorText)
    {
        return '[Cash Flow ID: ' . $model->getId() . '] ' . $errorText;
    }
}

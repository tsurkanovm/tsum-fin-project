<?php

namespace Tsum\CashFlow\Controller\Adminhtml\Incomes;

use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Controller\ResultInterface;
use Tsum\CashFlow\Model\Incomes;
use Tsum\CashFlow\Model\ResourceModel\Incomes as IncomesResource;
use Tsum\CashFlow\Model\IncomesFactory;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class InlineEdit extends \Magento\Backend\App\Action
{
    const ADMIN_RESOURCE = 'Tsum_CashFlow::incomes';

    /**
     * @var IncomesFactory
     */
    private $incomesFactory;

    /**
     * @var IncomesResource
     */
    private $incomesResource;

    /**
     * @var JsonFactory
     */
    private $jsonFactory;

    /**
     * @param Context $context
     * @param IncomesFactory $incomesFactory
     * @param IncomesResource $incomesResource
     * @param JsonFactory $jsonFactory
     */
    public function __construct(
        Context $context,
        IncomesFactory $incomesFactory,
        IncomesResource $incomesResource,
        JsonFactory $jsonFactory
    ) {
        parent::__construct($context);
        $this->incomesFactory = $incomesFactory;
        $this->incomesResource = $incomesResource;
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

        $incomes = $this->getRequest()->getParam('items', []);
        if (!($this->getRequest()->getParam('isAjax') && count($incomes))) {
            return $resultJson->setData([
                'messages' => [__('Please correct the data sent.')],
                'error' => true,
            ]);
        }

        foreach (array_keys($incomes) as $itemId) {
            /** @var Incomes $model */
            $model = $this->incomesFactory->create();
            $this->incomesResource->load($model, $itemId);
            try {
                //$model->setData($incomes[$itemId]);
                $model->addData($incomes[$itemId]);
                $this->incomesResource->save($model);
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
     * @param Incomes $model
     * @param string $errorText
     * @return string
     */
    protected function getErrorWithPageId(Incomes $model, $errorText)
    {
        return '[Cash Flow ID: ' . $model->getId() . '] ' . $errorText;
    }
}

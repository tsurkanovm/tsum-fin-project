<?php

namespace Tsum\CashFlowImport\Controller\Adminhtml\Staging;

use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Controller\ResultInterface;
use Tsum\CashFlowImport\Model\ResourceModel\Staging as StageResource;
use Tsum\CashFlowImport\Model\Staging;
use Tsum\CashFlowImport\Model\StagingFactory;

class InlineEdit implements HttpPostActionInterface
{
    public function __construct(
        private readonly RequestInterface $request,
        private readonly StagingFactory $stagingFactory,
        private readonly StageResource $stagingResource,
        private readonly JsonFactory $jsonFactory,
    ) {
    }

    public function execute(): ResultInterface
    {
        $resultJson = $this->jsonFactory->create();
        $error = false;
        $messages = [];

        $staging = $this->request->getParam('items', []);
        if (!($this->request->getParam('isAjax') && count($staging))) {
            return $resultJson->setData([
                'messages' => [__('Please correct the data sent.')],
                'error' => true,
            ]);
        }

        foreach (array_keys($staging) as $itemId) {
            /** @var Staging $model */
            $model = $this->stagingFactory->create();
            $this->stagingResource->load($model, $itemId);
            try {
                $model->setData($staging[$itemId]);
                $this->stagingResource->save($model);
            } catch (\Exception $e) {
                $messages[] = $this->getErrorWithModelId($model, $e->getMessage());
                $error = true;
            }
        }

        return $resultJson->setData([
            'messages' => $messages,
            'error' => $error
        ]);
    }

    protected function getErrorWithModelId(Staging $model, string $errorText): string
    {
        return '[Staging Cash Flow ID: ' . $model->getId() . '] ' . $errorText;
    }
}

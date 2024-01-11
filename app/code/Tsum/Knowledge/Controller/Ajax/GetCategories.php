<?php

namespace Tsum\Knowledge\Controller\Ajax;

use Magento\Catalog\Model\ResourceModel\Category\CollectionFactory;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\Result\JsonFactory;

class GetCategories implements HttpGetActionInterface
{
    public function __construct(
        private readonly JsonFactory       $resultJsonFactory,
        private readonly CollectionFactory $categoryCollectionFactory
    ) {
    }

    public function execute()
    {
        try {
            $collection = $this->categoryCollectionFactory->create();
            $collection->addAttributeToSelect('name')
                ->addAttributeToFilter('is_active', '1')
                ->setOrder('name', 'ASC');

            // Filter to get only last level categories
            $collection->getSelect()->where('children_count = 0');

            $categories = [];
            foreach ($collection as $category) {
                $categories[] = [
                    'id' => $category->getId(),
                    'name' => $category->getName()
                ];
            }

            $response = [
                'success' => true,
                'errorMessage' => '',
                'result' => $categories
            ];

        } catch (\Exception $e) {
            $response = [
                'success' => false,
                'errorMessage' => __('An error occurred while loading tax rates.')
            ];
        }
        $result = $this->resultJsonFactory->create();

        return $result->setData($response);
    }
}

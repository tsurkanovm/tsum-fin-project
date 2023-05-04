<?php
declare(strict_types=1);

namespace Tsum\CashFlow\Model\Source;

use Magento\Framework\Data\OptionSourceInterface;
use Tsum\CashFlow\Model\ResourceModel\Project\Collection;
use Tsum\CashFlow\Model\ResourceModel\Project\CollectionFactory;

class Project implements OptionSourceInterface
{
    /**
     * @var CollectionFactory
     */
    private $collectionFactory;

    /**
     * @param CollectionFactory $collectionFactory
     */
    public function __construct(
        CollectionFactory $collectionFactory
    ) {
        $this->collectionFactory = $collectionFactory;
    }

    /**
     * Get Grid row type array for option element.
     * @return array
     */
    private function getOptions() : array
    {
        $res = [];
        $res[] = ['value' => null, 'label' => __('Select project')];
        foreach ($this->getProjects() as $item) {
            $res[] = ['value' => $item['project_id'], 'label' => $item['title']];
        }

        return $res;
    }

    /**
     * {@inheritdoc}
     */
    public function toOptionArray()
    {
        return $this->getOptions();
    }

    public function getProjects() : array
    {
        /** @var Collection $collection */
        $collection = $this->collectionFactory->create();

        return $collection->getData();
    }
}

<?php
declare(strict_types=1);

namespace Tsum\CashFlow\Model\Source;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Data\OptionSourceInterface;
use Tsum\CashFlow\Api\Data\StorageInterface;
use Tsum\CashFlow\Api\StorageRepositoryInterface;

class Storage implements OptionSourceInterface
{
    /**
     * @var StorageRepositoryInterface
     */
    private $storageRepository;

    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteria;


    public function __construct(
        StorageRepositoryInterface $storageRepository,
        SearchCriteriaBuilder $searchCriteria
    ) {
        $this->storageRepository = $storageRepository;
        $this->searchCriteria = $searchCriteria;
    }

    /**
     * @return array
     */
    private function getOptions() : array
    {
        $res = [];
        /** @var StorageInterface $item */
        foreach ($this->getStorages() as $item) {
            $res[] = ['value' => $item->getId(), 'label' => $item->getTitle()];
        }

        return $res;
    }

    /**
     * {@inheritdoc}
     */
    public function toOptionArray() : array
    {
        return $this->getOptions();
    }

    /**
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getStorages() : array
    {
        $this->searchCriteria->addFilter('is_active', 1);

        return $this->storageRepository->getList($this->searchCriteria->create())->getItems();
    }
}

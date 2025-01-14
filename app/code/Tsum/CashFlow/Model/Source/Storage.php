<?php
declare(strict_types=1);

namespace Tsum\CashFlow\Model\Source;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Data\OptionSourceInterface;
use Magento\Framework\Exception\LocalizedException;
use Tsum\CashFlow\Api\Data\StorageInterface;
use Tsum\CashFlow\Api\StorageRepositoryInterface;

class Storage implements OptionSourceInterface
{

    public function __construct(
        private readonly StorageRepositoryInterface $storageRepository,
        private readonly SearchCriteriaBuilder $searchCriteria,
        private readonly bool $onlyActive = false,
    ) {
    }

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

    public function getStorages() : array
    {
        if ($this->onlyActive) {
            $this->searchCriteria->addFilter('is_active', 1);
        }

        return $this->storageRepository->getList($this->searchCriteria->create())->getItems();
    }
}
